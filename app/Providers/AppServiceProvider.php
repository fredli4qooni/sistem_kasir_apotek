<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Models\User; // <-- Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('role', function (string $roleToCheck) {
            $user = Auth::user();
            // Pastikan user ada dan merupakan instance dari model User kita
            if ($user && $user instanceof User) {
                return $user->hasRole($roleToCheck);
            }
            return false;
        });
    }
}