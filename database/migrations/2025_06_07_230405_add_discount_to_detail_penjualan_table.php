<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->decimal('diskon_item_persen', 5, 2)->nullable()->default(0)->after('sub_total'); // Misal: 10.00 untuk 10%
            $table->decimal('diskon_item_nominal', 15, 2)->nullable()->default(0)->after('diskon_item_persen');
            // Kolom sub_total akan tetap menyimpan (harga_saat_transaksi * jumlah) sebelum diskon item.
            // Atau, jika Anda ingin sub_total sudah termasuk diskon, maka kalkulasinya harus diubah.
            // Untuk konsistensi, mari kita anggap sub_total adalah (harga * qty) - diskon_item_nominal_efektif
        });
    }

    public function down(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropColumn('diskon_item_persen');
            $table->dropColumn('diskon_item_nominal');
        });
    }
};