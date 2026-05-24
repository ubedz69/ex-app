<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DHLService
{
    /**
     * @return array<string, mixed>
     */
    public function track(string $trackingNumber): array
    {
        $cacheKey = 'dhl:'.sha1($trackingNumber);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($trackingNumber): array {
            try {
                $apiKey = (string) config('services.dhl.key');
                $verify = ! (bool) config('services.dhl.ignore_ssl', false);
                $primaryHost = rtrim((string) config('services.dhl.base_url', 'https://api-eu.dhl.com'), '/');
                $fallbackHost = rtrim((string) config('services.dhl.fallback_base_url', 'https://api-test.dhl.com'), '/');

                $response = $this->requestTracking(
                    host: $primaryHost,
                    apiKey: $apiKey,
                    verify: $verify,
                    trackingNumber: $trackingNumber
                );

                if (! $response->successful() && $fallbackHost !== $primaryHost) {
                    $response = $this->requestTracking(
                        host: $fallbackHost,
                        apiKey: $apiKey,
                        verify: $verify,
                        trackingNumber: $trackingNumber
                    );
                }

                if (! $response->successful()) {
                    return [
                        'courier' => 'dhl',
                        'error' => 'Tidak dapat mengambil data pelacakan DHL saat ini',
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ];
                }

                $json = $response->json();

                if (! is_array($json)) {
                    return [
                        'courier' => 'dhl',
                        'error' => 'Format respons DHL tidak valid',
                    ];
                }

                return $json;
            } catch (RequestException $e) {
                report($e);

                return [
                    'courier' => 'dhl',
                    'error' => 'Tidak dapat mengambil data pelacakan DHL saat ini',
                    'status' => $e->response?->status(),
                    'body' => $e->response?->body(),
                ];
            } catch (\Throwable $e) {
                report($e);

                return [
                    'courier' => 'dhl',
                    'error' => 'Tidak dapat mengambil data pelacakan DHL saat ini',
                ];
            }
        });
    }

    private function requestTracking(
        string $host,
        string $apiKey,
        bool $verify,
        string $trackingNumber
    ): Response {
        return Http::withOptions([
            'verify' => $verify,
        ])->withHeaders([
            'DHL-API-Key' => $apiKey,
        ])
            ->timeout(8)
            ->retry(2, 100)
            ->get($host.'/track/shipments', [
                'trackingNumber' => $trackingNumber,
            ]);
    }
}
