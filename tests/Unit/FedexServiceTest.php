<?php

use App\Services\FedexService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

test('fedex service returns tracking data when token available', function () {
    Cache::flush();

    Http::fake([
        'https://apis.fedex.com/oauth/token' => Http::response(['access_token' => 'token123'], 200),
        'https://apis.fedex.com/track/v1/trackingnumbers' => Http::response([
            'output' => [
                'completeTrackResults' => [
                    [
                        'trackResults' => [
                            [
                                'trackingNumberInfo' => [
                                    'trackingNumber' => '123',
                                ],
                                'latestStatusDetail' => [
                                    'statusByLocale' => 'Picked up',
                                    'description' => 'Picked up',
                                    'code' => 'PU',
                                    'timestamp' => '2026-05-14T09:10:11Z',
                                ],
                                'scanEvents' => [
                                    [
                                        'date' => '2026-05-14T09:10:11Z',
                                        'eventDescription' => 'Shipment picked up',
                                        'scanLocation' => [
                                            'city' => 'Jakarta',
                                        ],
                                    ],
                                ],
                                'lastUpdatedDestinationAddress' => [
                                    'city' => 'Osaka',
                                ],
                                'originLocation' => [
                                    'locationContactAndAddress' => [
                                        'address' => [
                                            'city' => 'Jakarta',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $service = new FedexService;
    $res = $service->track('123');

    expect(is_array($res))->toBeTrue();

    if (! array_key_exists('shipments', $res)) {
        throw new \Exception('FedexService returned unexpected payload: '.var_export($res, true));
    }

    expect(is_array($res['shipments']))->toBeTrue();
    expect(count($res['shipments']))->toBeGreaterThan(0);
});

test('fedex service returns error when token fails', function () {
    Http::fake([
        'https://apis.fedex.com/oauth/token' => Http::response([], 500),
    ]);

    $service = new FedexService;
    $res = $service->track('123');

    expect(is_array($res))->toBeTrue();
    expect(isset($res['error']))->toBeTrue();
});
