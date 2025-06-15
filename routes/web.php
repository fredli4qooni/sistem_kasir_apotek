<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController; // Tambahkan ini
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import

Route::get('/', function () {
    if (Auth::check()) {
        // Jika pengguna sudah login, arahkan ke dashboard/home
        return redirect()->route('home');
    }
    // Jika pengguna belum login, arahkan ke halaman login
    return redirect()->route('login');
})->name('root'); // Beri nama rute jika perlu, misal 'root'

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // --- Fitur Kasir (Hanya untuk KASIR) ---
    Route::middleware(['role:kasir'])->group(function () {
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [PenjualanController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/struk/{id_penjualan}', [PenjualanController::class, 'showStruk'])->name('transaksi.struk');

    });


    // --- Manajemen Obat (Hanya untuk KASIR) ---
    Route::middleware(['role:kasir'])->group(function () {
        Route::resource('obat', ObatController::class);
        // API Search Obat (digunakan di kasir, jadi ikut rule kasir)
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