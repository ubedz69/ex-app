<?php

use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::prefix('tracking')->group(function () {

    Route::get('/', [TrackingController::class, 'index']);

    Route::post('/check', [TrackingController::class, 'track'])->middleware('throttle:10,1');

});
