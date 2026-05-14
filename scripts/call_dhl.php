<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$service = $app->make(App\Services\DHLService::class);
$number = $argv[1] ?? '1234567890';
$res = $service->track($number);
print_r($res);
