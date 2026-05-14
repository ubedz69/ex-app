<?php

namespace App\Services\Fedex;

use App\Services\Fedex\FedexTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedexService
{
    public function track($trackingNumber)
    {
        if (config('app.env') === 'testing') {
            return $this->trackWithoutCache($trackingNumber);
        }

        $cacheKey = 'fedex:'.sha1($trackingNumber);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($trackingNumber) {
            return $this->trackWithoutCache($trackingNumber);
        });
    }

    /**
     * @param string $trackingNumber
     * @return array<string,mixed>
     */
    private function trackWithoutCache($trackingNumber): array
    {
        try {
            $token = $this->getToken();

            if (empty($token)) {
                return ['error' => 'Tidak dapat memperoleh token FedEx'];
            }

            $verify = ! (bool) env('FEDEX_IGNORE_SSL', false);

            $response = Http::withToken($token)
                ->timeout(5)
                ->retry(2, 100)
                ->withOptions([
                    'verify' => $verify,
                ])
                ->post(
                    'https://apis.fedex.com/track/v1/trackingnumbers',
                    [
                        'trackingInfo' => [
                            [
                                'trackingNumberInfo' => [
                                    'trackingNumber' => $trackingNumber,
                                ],
                            ],
                        ],
                    ]
                );

            if (! $response->successful()) {
                return ['error' => 'FedEx API error', 'status' => $response->status(), 'body' => $response->body()];
            }

            $fedexJson = $response->json();

            if ((string) $trackingNumber === '871533663480') {
                try {
                    $dumpPath = storage_path('logs/fedex_raw_871533663480.json');
                    file_put_contents($dumpPath, json_encode($fedexJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                } catch (\Throwable $e) {
                    // ignore dump failures
                }
            }

            if (is_object($fedexJson)) {
                $fedexJson = (array) $fedexJson;
            }

            if (! is_array($fedexJson)) {
                return ['error' => 'Unexpected FedEx response format'];
            }

            return (new FedexTransformer())->transformTrack($fedexJson);
        } catch (\Throwable $e) {
            report($e);

            return ['error' => 'Terjadi kesalahan saat menghubungi FedEx'];
        }
    }

    private function getToken()
    {
        $debugPath = storage_path('logs/fedex_debug_runtime.log');

        try {
            $verify = ! (bool) env('FEDEX_IGNORE_SSL', false);

            file_put_contents(
                $debugPath,
                '['.date('c').'] fedex_token_request start verify='.(string) $verify.' ignoreSSL='.(string) env('FEDEX_IGNORE_SSL').PHP_EOL,
                FILE_APPEND
            );

            $response = Http::asForm()
                ->timeout(5)
                ->withOptions([
                    'verify' => $verify,
                ])
                ->post(
                    'https://apis.fedex.com/oauth/token',
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => env('FEDEX_API_KEY'),
                        'client_secret' => env('FEDEX_SECRET_KEY'),
                    ]
                );

            file_put_contents(
                $debugPath,
                '['.date('c').'] fedex_token_http status='.$response->status().' body='.substr((string) $response->body(), 0, 2000).PHP_EOL,
                FILE_APPEND
            );

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            if (! isset($data['access_token']) || empty($data['access_token'])) {
                file_put_contents(
                    $debugPath,
                    '['.date('c').'] fedex_token_missing access_token_present='.(isset($data['access_token']) ? 'yes' : 'no').PHP_EOL,
                    FILE_APPEND
                );

                return null;
            }

            file_put_contents(
                $debugPath,
                '['.date('c').'] fedex_token_success received access_token'.PHP_EOL,
                FILE_APPEND
            );

            return $data['access_token'];
        } catch (\Throwable $e) {
            file_put_contents(
                $debugPath,
                '['.date('c').'] fedex_token_exception class='.get_class($e).' message='.(string) $e->getMessage().PHP_EOL,
                FILE_APPEND
            );

            return null;
        }
    }
}
