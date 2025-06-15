<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat'; // Jika Anda menggunakan nama tabel singular

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'deskripsi',
        'satuan',
        'distributor',       
        'nomor_batch',        
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimal',
        'tanggal_kadaluarsa',
        // 'qr_code_path', // Kita akan tangani ini nanti jika ada upload file
    ];

    // Jika Anda ingin tanggal_kadaluarsa otomatis di-cast sebagai objek Carbon (untuk kemudahan format)
    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_obat');
    }
}
