<?php

use App\Http\Controllers\OtaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/upload', [OtaController::class, 'upload']);
    Route::post('/releases', [OtaController::class, 'createRelease']);
    Route::get('/check-update', [OtaController::class, 'checkUpdate']);
    Route::post('/events', [OtaController::class, 'logEvent']);
});
