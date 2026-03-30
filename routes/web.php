<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/apps', [DashboardController::class, 'apps'])->name('dashboard.apps');
Route::get('/dashboard/apps/{appName}', [DashboardController::class, 'appDetail'])->name('dashboard.app-detail');
Route::get('/dashboard/releases/{release}', [DashboardController::class, 'releaseDetail'])->name('dashboard.release-detail');


Route::post('/dashboard/releases/{release}/toggle', [DashboardController::class, 'toggleEnabled'])->name('dashboard.release-toggle');
Route::post('/dashboard/releases/{release}/rollout', [DashboardController::class, 'updateRollout'])->name('dashboard.release-rollout');
Route::post('/dashboard/releases/{release}/promote', [DashboardController::class, 'promote'])->name('dashboard.release-promote');
Route::post('/dashboard/releases/{release}/rollback', [DashboardController::class, 'rollback'])->name('dashboard.release-rollback');
Route::post('/dashboard/releases/{release}/make-current', [DashboardController::class, 'makeCurrent'])->name('dashboard.release-make-current');
