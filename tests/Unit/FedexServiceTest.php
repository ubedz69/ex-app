<?php

use App\Services\FedexService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

test('fedex service returns tracking data when token available', function () {
    Http::fake([
        'https://apis.fedex.com/oauth/token' => Http::response(['access_token' => 'token123'], 200),
        'https://apis.fedex.com/track/v1/trackingnumbers' => Http::response(['track' => 'ok'], 200),
    ]);

    $service = new FedexService;
    $res = $service->track('123');

    expect(is_array($res))->toBeTrue();
    expect(isset($res['track']))->toBeTrue();
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
