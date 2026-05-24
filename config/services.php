<?php

return [

    'dhl' => [
        'key' => env('DHL_API_KEY'),
        'base_url' => env('DHL_API_BASE_URL', 'https://api-eu.dhl.com'),
        'fallback_base_url' => env('DHL_API_FALLBACK_BASE_URL', 'https://api-test.dhl.com'),
        'ignore_ssl' => (bool) env('DHL_IGNORE_SSL', false),
    ],

    'fedex' => [
        'key' => env('FEDEX_API_KEY'),
        'secret' => env('FEDEX_SECRET_KEY'),
        'ignore_ssl' => (bool) env('FEDEX_IGNORE_SSL', false),
    ],

];
