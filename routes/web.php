<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Middleware\PenyewaMiddleware;

/*
|--------------------------------------------------------------------------
| Public Route (Visitor)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Utama (Dipakai oleh Breeze: route('dashboard'))
|--------------------------------------------------------------------------
| Otomatis mengarahkan user ke dashboard sesuai role.
| - Admin   -> /admin/dashboard
| - Manager -> /manager/dashboard
| - Penyewa -> dashboard.user (view dashboard.user)
*/

Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role && $user->role->name === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role && $user->role->name === 'Manager') {
        return redirect()->route('manager.dashboard');
    }

    // Default: Penyewa (User biasa)
    return view('dashboard.user');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Dashboard Manager
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', ManagerMiddleware::class])->group(function () {
    Route::get('/manager/dashboard', function () {
        return view('dashboard.manager');
    })->name('manager.dashboard');
});

/*
|--------------------------------------------------------------------------
| Dashboard Penyewa (opsional, selain /dashboard)
|--------------------------------------------------------------------------
| /dashboard sudah menampilkan dashboard.user untuk Penyewa,
| tapi route ini disediakan jika ingin URL khusus.
*/

Route::middleware(['auth', PenyewaMiddleware::class])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('dashboard.user');
    })->name('dashboard.user');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Semua user yang sudah login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
