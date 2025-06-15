<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_detail_penjualans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) { // Saya ganti 'detail_penjualans' jadi 'detail_penjualan'
            $table->id();
            $table->foreignId('id_penjualan')->constrained('penjualan')->onDelete('cascade'); // Foreign key ke tabel penjualan
            $table->foreignId('id_obat')->constrained('obat')->onDelete('restrict'); // Foreign key ke tabel obat, restrict delete jika obat terkait transaksi
            $table->integer('jumlah');
            $table->decimal('harga_satuan_saat_transaksi', 15, 2);
            $table->decimal('sub_total', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};