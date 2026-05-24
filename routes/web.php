<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

require __DIR__.'/blog.php';

Route::get('/', [HomeController::class, 'index']);

Route::get('/tracking', [TrackingController::class, 'index']);
Route::post('/tracking', [TrackingController::class, 'track'])->middleware('throttle:10,1');

Route::get('/about', [PageController::class, 'about']);
Route::get('/services', [PageController::class, 'services']);
Route::get('/contact', [PageController::class, 'contact']);
Route::post('/contact', [PageController::class, 'submitContact']);

Route::get('/generate-sitemap', function () {
    $baseUrl = 'https://rairakaexpress.com';

    Sitemap::create()
        ->add(
            Url::create($baseUrl.'/')
                ->setPriority(1.0)
                ->setChangeFrequency('daily')
        )
        ->add(
            Url::create($baseUrl.'/about')
                ->setPriority(0.7)
                ->setChangeFrequency('monthly')
        )
        ->add(
            Url::create($baseUrl.'/services')
                ->setPriority(0.8)
                ->setChangeFrequency('monthly')
        )
        ->add(
            Url::create($baseUrl.'/contact')
                ->setPriority(0.6)
                ->setChangeFrequency('monthly')
        )
        ->add(
            Url::create($baseUrl.'/tracking')
                ->setPriority(0.9)
                ->setChangeFrequency('daily')
        )
        ->add(
            Url::create($baseUrl.'/blog')
                ->setPriority(0.7)
                ->setChangeFrequency('weekly')
        )
        ->add(
            Url::create($baseUrl.'/blog/create')
                ->setPriority(0.1)
                ->setChangeFrequency('monthly')
        )
        ->writeToFile(public_path('sitemap.xml'));

    return 'Sitemap generated for '.$baseUrl;
})->middleware([
    'auth.basic',
    'throttle:3,1',
]);
