<?php

use App\Services\DHLService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

test('dhl service returns tracking data', function () {
    Cache::flush();

    Http::fake([
        'https://api-eu.dhl.com/track/shipments*' => Http::response(['shipments' => []], 200),
        'https://api-test.dhl.com/track/shipments*' => Http::response(['shipments' => []], 200),
    ]);

    $service = new DHLService;
    $res = $service->track('123');

    expect(is_array($res))->toBeTrue();
    expect(isset($res['shipments']))->toBeTrue();
});

test('dhl service returns error when api fails', function () {
    Cache::flush();

    Http::fake([
        'https://api-eu.dhl.com/track/shipments*' => Http::response('error', 500),
        'https://api-test.dhl.com/track/shipments*' => Http::response('error', 500),
    ]);

    $service = new DHLService;
    $res = $service->track('123');

    expect(is_array($res))->toBeTrue();
    expect(isset($res['error']))->toBeTrue();
});
