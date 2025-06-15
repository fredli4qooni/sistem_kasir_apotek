<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jangan lupa import Auth
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  // Kita akan menerima parameter role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah pengguna sudah login dan memiliki peran yang sesuai
        if (!Auth::check() || !$request->user()->hasRole($role)) {
            // Jika tidak, kembalikan ke halaman sebelumnya dengan pesan error,
            // atau redirect ke halaman tertentu, atau tampilkan halaman 403 (Forbidden).
            // return redirect('home')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            abort(403, 'ANDA TIDAK MEMILIKI IZIN UNTUK MENGAKSES HALAMAN INI.');
        }

        return $next($request);
    }
}