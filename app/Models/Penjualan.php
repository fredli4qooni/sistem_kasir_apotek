<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'nomor_transaksi',
        'id_user',
        'total_harga',
        'jumlah_bayar',
        'kembalian',
        'catatan',
        // created_at akan otomatis terisi
    ];

    // Relasi ke User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke DetailPenjualan
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }
}