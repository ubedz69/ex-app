<?php

namespace App\Services\Fedex;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedexService
{
    /**
     * @return array<string, mixed>
     */
    public function track(string $trackingNumber): array
    {
        if (app()->environment('testing')) {
            return $this->trackWithoutCache($trackingNumber);
        }

        $cacheKey = 'fedex:'.sha1($trackingNumber);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($trackingNumber): array {
            return $this->trackWithoutCache($trackingNumber);
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function trackWithoutCache(string $trackingNumber): array
    {
        try {
            $token = $this->getToken();

            if (empty($token)) {
                return ['error' => 'Tidak dapat memperoleh token FedEx'];
            }

            $verify = ! (bool) config('services.fedex.ignore_ssl', false);

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

            if (is_object($fedexJson)) {
                $fedexJson = (array) $fedexJson;
            }

            if (! is_array($fedexJson)) {
                return ['error' => 'Unexpected FedEx response format'];
            }

            return $this->normalizeTrackResponse($fedexJson);
        } catch (\Throwable $e) {
            report($e);

            return ['error' => 'Terjadi kesalahan saat menghubungi FedEx'];
        }
    }

    private function getToken(): ?string
    {
        try {
            $verify = ! (bool) config('services.fedex.ignore_ssl', false);

            $response = Http::asForm()
                ->timeout(5)
                ->withOptions([
                    'verify' => $verify,
                ])
                ->post(
                    'https://apis.fedex.com/oauth/token',
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => (string) config('services.fedex.key'),
                        'client_secret' => (string) config('services.fedex.secret'),
                    ]
                );

            if (! $response->successful()) {
                Log::warning('FedEx token request failed', [
                    'status' => $response->status(),
                ]);

                return null;
            }

            $data = $response->json();

            if (! isset($data['access_token']) || empty($data['access_token'])) {
                Log::warning('FedEx token missing access_token key');

                return null;
            }

            return $data['access_token'];
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $fedexJson
     * @return array<string, mixed>
     */
    private function normalizeTrackResponse(array $fedexJson): array
    {
        $output = $fedexJson['output'] ?? [];
        if (! is_array($output)) {
            $output = [];
        }

        $completeTrackResults = $output['completeTrackResults'] ?? [];
        if (! is_array($completeTrackResults)) {
            $completeTrackResults = [];
        }

        $normalizedCompleteTrackResults = [];

        foreach ($completeTrackResults as $completeTrackResult) {
            if (! is_array($completeTrackResult)) {
                continue;
            }

            $trackResults = $completeTrackResult['trackResults'] ?? [];
            if (! is_array($trackResults)) {
                $trackResults = [];
            }

            $normalizedTrackResults = [];

            foreach ($trackResults as $trackResult) {
                if (is_array($trackResult)) {
                    $normalizedTrackResults[] = $trackResult;
                }
            }

            $normalizedCompleteTrackResults[] = [
                'trackingNumber' => $completeTrackResult['trackingNumber'] ?? null,
                'trackResults' => $normalizedTrackResults,
            ];
        }

        $output['completeTrackResults'] = $normalizedCompleteTrackResults;
        $output['alerts'] = $output['alerts'] ?? null;

        $fedexJson['transactionId'] = (string) ($fedexJson['transactionId'] ?? '');
        $fedexJson['customerTransactionId'] = isset($fedexJson['customerTransactionId'])
            ? (string) $fedexJson['customerTransactionId']
            : '';
        $fedexJson['output'] = $output;

        return $fedexJson;
    }
}
