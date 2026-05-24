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
        throw new Exception('FedexService returned unexpected payload: '.var_export($res, true));
    }

    expect(is_array($res['shipments']))->toBeTrue();
    expect(count($res['shipments']))->toBeGreaterThan(0);
});

test('fedex timeline only includes real status events', function () {
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
                                'scanEvents' => [
                                    [
                                        'date' => '2026-05-14T09:10:11Z',
                                        'eventDescription' => 'Shipment picked up',
                                        'scanLocation' => [
                                            'city' => 'Jakarta',
                                        ],
                                    ],
                                ],
                                'estimatedDeliveryTimeWindow' => [
                                    'window' => [
                                        'begins' => '2026-05-15T08:00:00Z',
                                        'ends' => '2026-05-16T18:00:00Z',
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

    expect($res)->toHaveKey('shipments');
    expect($res['shipments'][0]['shipmentPeriod']['start'] ?? null)->toBe('2026-05-14T09:10:11Z');
    expect($res['shipments'][0]['shipmentPeriod']['end'] ?? null)->toBe('2026-05-16T18:00:00Z');

    $events = $res['shipments'][0]['events'] ?? [];
    $eventDates = array_map(
        fn (array $event): string => substr((string) ($event['timestamp'] ?? ''), 0, 10),
        $events
    );

    expect($eventDates)->toContain('2026-05-14');
    expect($eventDates)->not->toContain('2026-05-15');
    expect(count($events))->toBe(1);
});

test('fedex service returns raw fedex-like response shape', function () {
    Cache::flush();

    Http::fake([
        'https://apis.fedex.com/oauth/token' => Http::response(['access_token' => 'token123'], 200),
        'https://apis.fedex.com/track/v1/trackingnumbers' => Http::response([
            'transactionId' => '624deea6-b709-470c-8c39-4b5511281492',
            'customerTransactionId' => 'AnyCo_order123456789',
            'output' => [
                'completeTrackResults' => [
                    [
                        'trackingNumber' => '123456789012',
                        'trackResults' => [
                            [
                                'trackingNumberInfo' => [
                                    'trackingNumber' => '128667043726',
                                    'carrierCode' => 'FDXE',
                                ],
                                'latestStatusDetail' => [
                                    'statusByLocale' => 'Picked up',
                                    'description' => 'Picked up',
                                    'code' => 'PU',
                                ],
                                'scanEvents' => [
                                    [
                                        'date' => '2018-02-02T12:01:00-07:00',
                                        'eventDescription' => 'Picked Up',
                                        'eventType' => 'PU',
                                        'scanLocation' => [
                                            'city' => 'SEATTLE',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'alerts' => 'TRACKING.DATA.NOTFOUND -  Tracking data unavailable',
            ],
        ], 200),
    ]);

    $service = new FedexService;
    $res = $service->trackRaw('123456789012');

    expect(is_array($res))->toBeTrue();
    expect($res)->toHaveKeys([
        'transactionId',
        'customerTransactionId',
        'output',
    ]);
    expect($res['output'])->toHaveKey('completeTrackResults');
    expect(is_array($res['output']['completeTrackResults']))->toBeTrue();
    expect($res['output']['completeTrackResults'])->not->toBeEmpty();
    expect($res['output']['completeTrackResults'][0])->toHaveKeys([
        'trackingNumber',
        'trackResults',
    ]);
});

test('fedex service returns error when token fails', function () {
    Cache::flush();

    Http::fake([
        'https://apis.fedex.com/oauth/token' => Http::response([], 500),
    ]);

    $service = new FedexService;
    $res = $service->track('123');

    expect(is_array($res))->toBeTrue();
    expect(isset($res['error']))->toBeTrue();
});
