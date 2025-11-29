<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Role;  // atau use untuk service lainnya


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    $this->app->bind('role', function ($app) {
        return new Role();  // Pastikan Role adalah class yang benar
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
