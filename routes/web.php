<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Route::get('/', [HomeController::class, 'index']);

Route::get('/tracking', [TrackingController::class, 'index']);
Route::post('/tracking', [TrackingController::class, 'track'])->middleware('throttle:10,1');

Route::get('/about', [PageController::class, 'about']);
Route::get('/services', [PageController::class, 'services']);
Route::get('/contact', [PageController::class, 'contact']);
Route::post('/contact', [PageController::class, 'submitContact']);

Route::get('/generate-sitemap', function () {
    Sitemap::create()
        ->add(
            Url::create('/')
                ->setPriority(1.0)
        )
        ->add(
            Url::create('/tracking')
                ->setPriority(0.9)
        )
        ->add(
            Url::create('/cek-resi-dhl')
                ->setPriority(0.8)
        )
        ->add(
            Url::create('/cek-resi-fedex')
                ->setPriority(0.8)
        )
        ->writeToFile(
            public_path('sitemap.xml')
        );

    return 'Sitemap generated!';
});
