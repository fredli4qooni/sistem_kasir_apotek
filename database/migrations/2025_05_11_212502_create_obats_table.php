<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_obats_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat', function (Blueprint $table) { // Saya ganti 'obats' jadi 'obat' agar lebih natural dalam Bahasa Indonesia
            $table->id();
            $table->string('kode_obat')->unique();
            $table->string('nama_obat');
            $table->text('deskripsi')->nullable();
            $table->string('satuan'); // Misal: Tablet, Botol, Strip
            $table->decimal('harga_beli', 15, 2); // 15 digit total, 2 digit di belakang koma
            $table->decimal('harga_jual', 15, 2);
            $table->integer('stok');
            $table->integer('stok_minimal')->default(10);
            $table->date('tanggal_kadaluarsa');
            $table->string('qr_code_path')->nullable();
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};