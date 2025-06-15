<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_penjualans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) { // Saya ganti 'penjualans' jadi 'penjualan'
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade'); // Foreign key ke tabel users
            $table->decimal('total_harga', 15, 2);
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->text('catatan')->nullable();
            $table->timestamps(); // created_at akan jadi tanggal transaksi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};