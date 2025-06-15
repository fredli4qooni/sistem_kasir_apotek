<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\DetailPenjualan; // Import DetailPenjualan
use Illuminate\Support\Facades\DB; // Import DB facade
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $data = [];
        $now = Carbon::now();

        if ($user->isKasir()) {
            $obatStokMenipis = Obat::whereRaw('stok <= stok_minimal')
                                   ->orderBy('stok', 'asc')
                                   ->take(5)->get();
            $tanggalCekExpired = $now->copy()->addDays(30);
            $obatAkanExpired = Obat::where('tanggal_kadaluarsa', '<=', $tanggalCekExpired)
                                 ->orderBy('tanggal_kadaluarsa', 'asc')
                                 ->take(5)->get();
            $data['obatStokMenipis'] = $obatStokMenipis;
            $data['obatAkanExpired'] = $obatAkanExpired;

            // Transaksi terakhir oleh kasir ini
            $data['transaksiTerakhirKasir'] = Penjualan::where('id_user', $user->id)
                                                      ->orderBy('created_at', 'desc')
                                                      ->take(3)
                                                      ->get();

        } elseif ($user->isPemilik()) {
            $today = $now->copy()->toDateString();
            $startOfMonth = $now->copy()->startOfMonth()->toDateString();
            $endOfMonth = $now->copy()->endOfMonth()->toDateString();

            $data['totalPendapatanHariIni'] = Penjualan::whereDate('created_at', $today)->sum('total_harga');
            $data['jumlahTransaksiHariIni'] = Penjualan::whereDate('created_at', $today)->count();
            $data['totalPendapatanBulanIni'] = Penjualan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_harga');
            $data['jumlahTransaksiBulanIni'] = Penjualan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            $penjualan7HariLabels = [];
            $penjualan7HariValues = [];
            for ($i = 6; $i >= 0; $i--) {
                $tanggal = $now->copy()->subDays($i);
                $penjualan7HariLabels[] = $tanggal->format('d M');
                $penjualan7HariValues[] = Penjualan::whereDate('created_at', $tanggal->toDateString())->sum('total_harga');
            }
            $data['penjualan7HariLabels'] = $penjualan7HariLabels;
            $data['penjualan7HariValues'] = $penjualan7HariValues;

            // Produk terlaris bulan ini (berdasarkan jumlah unit terjual)
            $data['produkTerlarisBulanIni'] = DetailPenjualan::join('obat', 'detail_penjualan.id_obat', '=', 'obat.id')
                ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id')
                ->select('obat.nama_obat', DB::raw('SUM(detail_penjualan.jumlah) as total_terjual'))
                ->whereMonth('penjualan.created_at', $now->month)
                ->whereYear('penjualan.created_at', $now->year)
                ->groupBy('obat.id', 'obat.nama_obat') // Include obat.id for safety if names are not unique
                ->orderBy('total_terjual', 'desc')
                ->take(5)
                ->get();
        }
        return view('home', $data);
    }
}