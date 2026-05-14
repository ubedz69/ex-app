<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DHLService
{
    public function track($trackingNumber)
    {
        $cacheKey = 'dhl:'.sha1($trackingNumber);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($trackingNumber) {
            try {
                $apiKey = env('DHL_API_KEY');

                // Choose host by env: tests and default expect api-eu.dhl.com
                $host = env('DHL_USE_TEST_API', false) ? 'https://api-test.dhl.com' : 'https://api-eu.dhl.com';

                $verify = ! (bool) env('DHL_IGNORE_SSL', false);

                $response = Http::withOptions([
                    'verify' => $verify,
                ])->withHeaders([
                    'DHL-API-Key' => $apiKey,
                ])
                    ->timeout(8)
                    ->retry(2, 100)
                    ->get($host.'/track/shipments', [
                        'trackingNumber' => $trackingNumber,
                    ]);

                if (! $response->successful()) {
                    return ['error' => 'DHL API error', 'status' => $response->status(), 'body' => $response->body()];
                }

                return $response->json();
            } catch (\Illuminate\Http\Client\RequestException $e) {
                // If the HTTP client threw a RequestException we can inspect the response
                report($e);
                $resp = method_exists($e, 'response') ? $e->response : null;

                return [
                    'error' => 'DHL API request failed',
                    'status' => $resp ? $resp->status() : null,
                    'body' => $resp ? $resp->body() : null,
                ];
            } catch (\Throwable $e) {
                report($e);

                return ['error' => 'Terjadi kesalahan saat menghubungi DHL'];
            }
        });
    }
}
