<?php

use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {
    $publicRobotsPath = public_path('robots.txt');
    $projectRobotsPath = base_path('robots.txt');
    $robotsPath = file_exists($publicRobotsPath) ? $publicRobotsPath : $projectRobotsPath;

    if (! is_file($robotsPath)) {
        abort(404);
    }

    $robotsContent = file_get_contents($robotsPath);

    return response(
        is_string($robotsContent) ? $robotsContent : ''
    )->header('Content-Type', 'text/plain');
});
