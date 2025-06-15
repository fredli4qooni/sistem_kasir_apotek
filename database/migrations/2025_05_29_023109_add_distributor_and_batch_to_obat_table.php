<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->string('distributor')->nullable()->after('satuan'); // Kolom untuk nama distributor, boleh kosong
            $table->string('nomor_batch')->nullable()->after('distributor'); // Kolom untuk nomor batch, boleh kosong
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn('distributor');
            $table->dropColumn('nomor_batch');
        });
    }
};