<?php

namespace App\Providers;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure a root route exists (helps tests and default app behavior)
        Route::middleware('web')->group(function () {
            Route::get('/', [HomeController::class, 'index']);
        });
    }
}
