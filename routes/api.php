<?php

use App\Http\Controllers\OtaController;
use Illuminate\Support\Facades\Route;

Route::post('/upload', [OtaController::class, 'upload']);
Route::post('/releases', [OtaController::class, 'createRelease']);
Route::get('/check-update', [OtaController::class, 'checkUpdate']);
