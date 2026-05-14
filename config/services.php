<?php

return [

    'dhl' => [
        'key' => env('DHL_API_KEY'),
    ],

    'fedex' => [
        'key' => env('FEDEX_API_KEY'),
        'secret' => env('FEDEX_SECRET_KEY'),
    ],

];
