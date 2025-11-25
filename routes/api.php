<?php

use App\Http\Controllers\Api\Admin\AddonController;
use App\Http\Controllers\Api\Admin\FeatureController;
use App\Http\Controllers\Api\Admin\StudioController as AdminStudioController;
use App\Http\Controllers\Api\ReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes – MODE DEV / TEST (TANPA AUTH)
|--------------------------------------------------------------------------
| Semua endpoint di bawah ini sengaja dibuat TANPA middleware auth supaya
| gampang dites lewat VS Code REST Client (test.http) tanpa harus login.
| Nanti kalau sudah beres logika bisnis, auth & role bisa diaktifkan lagi.
*/

// ---------- PUBLIC: LIST & DETAIL STUDIO ----------
Route::get('/studios', [AdminStudioController::class, 'index']);
Route::get('/studios/{studio}', [AdminStudioController::class, 'show']);

// ---------- ADMIN AREA (CRUD MASTER DATA) ----------
Route::prefix('admin')->group(function () {
    // CRUD Studio (kecuali index & show, karena sudah di atas)
    Route::apiResource('studios', AdminStudioController::class)->except(['index', 'show']);

    // CRUD Addon
    Route::apiResource('addons', AddonController::class);

    // CRUD Feature
    Route::apiResource('features', FeatureController::class);
});

// ---------- RESERVATION / BOOKING ----------
Route::get('/reservations', [ReservationController::class, 'index']);
Route::post('/reservations', [ReservationController::class, 'store']);
Route::patch('/reservations/{reservation}', [ReservationController::class, 'update']);
Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);
Route::post('/reservations/{reservation}/checkin', [ReservationController::class, 'checkin']);
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);
