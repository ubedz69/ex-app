<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FedexService
{
    public function track($trackingNumber)
    {
        $cacheKey = 'fedex:'.sha1($trackingNumber);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($trackingNumber) {
            try {
                $token = $this->getToken();

                if (empty($token)) {
                    return ['error' => 'Tidak dapat memperoleh token FedEx'];
                }

                $response = Http::withToken($token)
                    ->timeout(5)
                    ->retry(2, 100)
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

                return $response->json();
            } catch (\Throwable $e) {
                report($e);

                return ['error' => 'Terjadi kesalahan saat menghubungi FedEx'];
            }
        });
    }

    private function getToken()
    {
        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(
                    'https://apis.fedex.com/oauth/token',
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => env('FEDEX_API_KEY'),
                        'client_secret' => env('FEDEX_SECRET_KEY'),
                    ]
                );

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            return $data['access_token'] ?? null;
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }
}
