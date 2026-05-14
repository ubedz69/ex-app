<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DHLService
{
    public function track($trackingNumber)
    {
        try {
            $apiKey = env('DHL_API_KEY');

            // Always use api-eu.dhl.com to match verified result from DHL endpoint
            $primaryHost = 'https://api-eu.dhl.com';
            $fallbackHost = 'https://api-eu.dhl.com';

            $verify = ! (bool) env('DHL_IGNORE_SSL', false);

            $response = Http::withOptions([
                'verify' => $verify,
            ])->withHeaders([
                'DHL-API-Key' => $apiKey,
            ])
                ->timeout(8)
                ->retry(2, 100)
                ->get($primaryHost.'/track/shipments', [
                    'trackingNumber' => $trackingNumber,
                ]);

            if (! $response->successful()) {
                $response = Http::withOptions([
                    'verify' => $verify,
                ])->withHeaders([
                    'DHL-API-Key' => $apiKey,
                ])
                    ->timeout(8)
                    ->retry(2, 100)
                    ->get($fallbackHost.'/track/shipments', [
                        'trackingNumber' => $trackingNumber,
                    ]);
            }

            if (! $response->successful()) {
                $json = null;
                try {
                    $json = $response->json();
                } catch (\Throwable $ignore) {
                    $json = null;
                }

                return [
                    'courier' => 'dhl',
                    'error' => 'Tidak dapat mengambil data pelacakan DHL saat ini',
                    'status' => $response->status(),
                    'raw' => [
                        'body' => $response->body(),
                        'json' => $json,
                    ],
                ];
            }

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            report($e);

            $resp = method_exists($e, 'response') ? $e->response : null;

            $json = null;
            $body = null;
            $status = null;

            if ($resp) {
                $status = $resp->status();
                $body = $resp->body();

                try {
                    $json = $resp->json();
                } catch (\Throwable $ignore) {
                    $json = null;
                }
            }

            return [
                'courier' => 'dhl',
                'error' => 'Tidak dapat mengambil data pelacakan DHL saat ini',
                'status' => $status,
                'raw' => [
                    'body' => $body,
                    'json' => $json,
                ],
            ];
        } catch (\Throwable $e) {
            report($e);

            return [
                'courier' => 'dhl',
                'error' => 'Tidak dapat mengambil data pelacakan DHL saat ini',
            ];
        }
    }
}
