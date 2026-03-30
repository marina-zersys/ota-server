<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/apps', [DashboardController::class, 'apps'])->name('dashboard.apps');
Route::get('/dashboard/apps/{appName}', [DashboardController::class, 'appDetail'])->name('dashboard.app-detail');
Route::get('/dashboard/releases/{release}', [DashboardController::class, 'releaseDetail'])->name('dashboard.release-detail');
Route::delete('/dashboard/releases/{release}', [DashboardController::class, 'destroyRelease'])->name('dashboard.release-destroy');
