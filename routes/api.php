<?php

use App\Http\Controllers\Api\Admin\AddonController;
use App\Http\Controllers\Api\Admin\FeatureController;
use App\Http\Controllers\Api\Admin\StudioController as AdminStudioController;
use App\Http\Controllers\Api\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/me', function (Request $request) {
    return $request->user();
});

// PUBLIC / MEMBER: list & detail studio
Route::get('/studios', [AdminStudioController::class, 'index']);
Route::get('/studios/{studio}', [AdminStudioController::class, 'show']);

// RESERVASI (MEMBER/ADMIN)
Route::middleware('auth')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/me/reservations', [ReservationController::class, 'myReservations']);

    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);
    Route::post('/reservations/{reservation}/checkin', [ReservationController::class, 'checkin']);
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);
});

// ADMIN AREA (CRUD master data)
Route::prefix('admin')
    ->middleware(['auth', 'role:admin|manager'])
    ->group(function () {
        Route::apiResource('studios', AdminStudioController::class)->except(['index', 'show']);
        Route::apiResource('addons', AddonController::class);
        Route::apiResource('features', FeatureController::class);
    });
