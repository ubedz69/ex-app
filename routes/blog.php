<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/create', [BlogController::class, 'create'])->middleware('auth.basic');
Route::post('/blog', [BlogController::class, 'store'])->middleware([
    'auth.basic',
    'throttle:10,1',
]);
