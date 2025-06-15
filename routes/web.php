<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

// Sederhanakan route root untuk debugging
Route::get('/', function () {
    return redirect()->route('login');
})->name('root');

Auth::routes();

// Test route home tanpa custom middleware dulu
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Debug route untuk cek session
    Route::get('/debug-session', function() {
        return response()->json([
            'user' => Auth::user(),
            'session_id' => session()->getId(),
            'is_authenticated' => Auth::check(),
            'session_data' => session()->all()
        ]);
    })->name('debug.session');
});

// Sementara comment out semua route dengan custom middleware
/*
Route::middleware(['auth'])->group(function () {
    // --- Fitur Kasir (Hanya untuk KASIR) ---
    Route::middleware(['role:kasir'])->group(function () {
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [PenjualanController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/struk/{id_penjualan}', [PenjualanController::class, 'showStruk'])->name('transaksi.struk');
    });

    // --- Manajemen Obat (Hanya untuk KASIR) ---
    Route::middleware(['role:kasir'])->group(function () {
        Route::resource('obat', ObatController::class);
        Route::get('/api/obat/search', [ObatController::class, 'search'])->name('api.obat.search');
    });

    // --- Laporan Penjualan (Bisa diakses oleh KASIR dan PEMILIK) ---
    Route::get('/laporan/penjualan', [LaporanController::class, 'index'])->name('laporan.penjualan.index');
    Route::get('/laporan/penjualan/data', [LaporanController::class, 'getData'])->name('laporan.penjualan.data');
    Route::get('/laporan/penjualan/export', [LaporanController::class, 'exportExcel'])->name('laporan.penjualan.export'); 

    // --- Route untuk Profil Pengguna ---
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
});
*/