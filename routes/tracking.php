use App\Http\Controllers\TrackingController;

Route::prefix('tracking')->group(function () {

    Route::get('/', [
        TrackingController::class,
        'index'
    ]);

    Route::post('/check', [
        TrackingController::class,
        'track'
    ]);

});