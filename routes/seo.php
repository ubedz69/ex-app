<?php

use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {

    return response(
        file_get_contents(
            public_path('robots.txt')
        )
    )->header('Content-Type', 'text/plain');

});