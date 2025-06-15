<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

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
        // Konfigurasi untuk Railway hosting - HTTPS forcing
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Deteksi proxy dari Railway dan force HTTPS
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
        }
        
        // Alternatif jika Railway menggunakan header lain
        if (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') {
            URL::forceScheme('https');
        }

        // Custom Blade directive untuk role checking
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