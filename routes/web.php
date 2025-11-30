<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Middleware\PenyewaMiddleware;

use App\Http\Controllers\Web\StudioWebController;
use App\Http\Controllers\Web\UserDashboardController;

use App\Http\Controllers\Admin\StudioAdminController;
use App\Http\Controllers\Admin\AddonAdminController;
use App\Http\Controllers\Admin\FeatureAdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Manager\ManagerController;  // Pastikan ini ada

/*
|-------------------------------------------------------------------------- 
| Public
|-------------------------------------------------------------------------- 
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|-------------------------------------------------------------------------- 
| Main Dashboard (Breeze default): /dashboard
|-------------------------------------------------------------------------- 
| Redirect sesuai role:
| Admin   -> /admin/dashboard
| Manager -> /manager/dashboard
| User    -> /user/dashboard
*/
Route::middleware('auth')->get('/dashboard', function () {
    $roleName = auth()->user()->role?->name;

    if ($roleName === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($roleName === 'Manager') {
        return redirect()->route('manager.dashboard');
    }

    return redirect()->route('user.dashboard');
})->name('dashboard');

/*
|-------------------------------------------------------------------------- 
| Admin
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('studios', StudioAdminController::class)->except(['show']);
        Route::patch('studios/{studio}/toggle-status', [StudioAdminController::class, 'toggleStatus'])
            ->name('studios.toggleStatus');

        Route::resource('addons', AddonAdminController::class)->except(['show']);
        Route::resource('features', FeatureAdminController::class)->except(['show']);

        // Laporan reservasi
        Route::get('/reports', [ReportsController::class, 'reservations'])->name('reports.index');
        Route::get('/reports/reservations', [ReportsController::class, 'reservations'])->name('reports.reservations');

        // Detail reservasi
        Route::get('/reservations/{reservation}', [ReportsController::class, 'show'])->name('reservations.show');

        // Update status reservasi
        Route::patch('/reservations/{reservation}/status', [ReportsController::class, 'updateStatus'])->name('reservations.updateStatus');

    });

/*
|-------------------------------------------------------------------------- 
| Manager
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth', ManagerMiddleware::class])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [ManagerController::class, 'reports'])->name('reports');
        
        // Route untuk menampilkan form tambah admin
        Route::get('/team', [ManagerController::class, 'team'])->name('team');
        Route::get('/team/create', [ManagerController::class, 'create'])->name('team.create'); // Pastikan route ini ada
        Route::post('/team', [ManagerController::class, 'store'])->name('team.store');
        
        // Route untuk memproses update admin
        Route::get('/team/{admin}/edit', [ManagerController::class, 'edit'])->name('team.edit');
        Route::put('/team/{admin}', [ManagerController::class, 'update'])->name('team.update');
        
        // Route untuk toggle status dan delete admin
        Route::patch('/team/{admin}/toggle-status', [ManagerController::class, 'toggleStatus'])->name('team.toggleStatus');
        Route::delete('/team/{admin}', [ManagerController::class, 'delete'])->name('team.delete');
        
        // Route profile manager
        Route::get('/profile', [ManagerController::class, 'profile'])->name('profile');
        Route::patch('/profile', [ManagerController::class, 'updateProfile'])->name('profile.update');
    });

/*
|-------------------------------------------------------------------------- 
| User (Penyewa) - Semua URL user jadi rapi: /user/...
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth', PenyewaMiddleware::class])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // studios
        Route::get('/studios', [StudioWebController::class, 'index'])->name('studios.index');
        Route::get('/studios/{studio}', [StudioWebController::class, 'show'])->name('studios.show');

        // buat reservasi dari halaman studio detail
        Route::post('/studios/{studio}/reservations', [StudioWebController::class, 'storeReservation'])
            ->name('reservations.store');

        // reservations list/detail/cancel
        Route::get('/reservations', [StudioWebController::class, 'myReservations'])->name('reservations.index');
        Route::get('/reservations/{reservation}', [StudioWebController::class, 'showReservation'])->name('reservations.show');
        Route::post('/reservations/{reservation}/cancel', [StudioWebController::class, 'cancelReservation'])->name('reservations.cancel');
    });

/*
|-------------------------------------------------------------------------- 
| Legacy routes (biar link lama gak error) - OPTIONAL tapi sangat membantu
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth'])->group(function () {
    // lama: /studios (tanpa /user)
    Route::get('/studios', fn () => redirect()->route('user.studios.index'))->name('studios.index');
    Route::get('/studios/{studio}', fn ($studio) => redirect()->route('user.studios.show', $studio))->name('studios.show');

    // lama: /my/reservations
    Route::get('/my/reservations', fn () => redirect()->route('user.reservations.index'))->name('my.reservations.index');
    Route::get('/my/reservations/{reservation}', fn ($reservation) => redirect()->route('user.reservations.show', $reservation))->name('my.reservations.show');
    Route::post('/my/reservations/{reservation}/cancel', fn ($reservation) => redirect()->route('user.reservations.cancel', $reservation))->name('my.reservations.cancel');
});

/*
|-------------------------------------------------------------------------- 
| Profile (All logged-in users)
|-------------------------------------------------------------------------- 
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
