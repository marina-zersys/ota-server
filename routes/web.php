<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/dashboard'));

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Logout (auth only)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard routes (auth only)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/apps', [DashboardController::class, 'apps'])->name('dashboard.apps');
    Route::get('/dashboard/apps/{appName}', [DashboardController::class, 'appDetail'])->name('dashboard.app-detail');
    Route::get('/dashboard/releases/{release}', [DashboardController::class, 'releaseDetail'])->name('dashboard.release-detail');

    Route::post('/dashboard/releases/{release}/toggle', [DashboardController::class, 'toggleEnabled'])->name('dashboard.release-toggle');
    Route::post('/dashboard/releases/{release}/rollout', [DashboardController::class, 'updateRollout'])->name('dashboard.release-rollout');
    Route::post('/dashboard/releases/{release}/promote', [DashboardController::class, 'promote'])->name('dashboard.release-promote');
    Route::post('/dashboard/releases/{release}/rollback', [DashboardController::class, 'rollback'])->name('dashboard.release-rollback');
    Route::post('/dashboard/releases/{release}/make-current', [DashboardController::class, 'makeCurrent'])->name('dashboard.release-make-current');
});
