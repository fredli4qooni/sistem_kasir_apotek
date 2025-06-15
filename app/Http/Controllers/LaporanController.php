<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan; // Import DetailPenjualan
use App\Models\Obat;            // Import Obat
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Exports\PenjualanExport; // <-- Import kelas Export
use Maatwebsite\Excel\Facades\Excel; // <-- Import Facade Excel

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.penjualan_index');
    }

    public function getData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_laporan' => 'required|in:bulanan_detail_harian,tahunan_detail_bulanan,harian_spesifik',
            'bulan' => 'required_if:jenis_laporan,bulanan_detail_harian|nullable|date_format:Y-m',
            'tahun' => 'required_if:jenis_laporan,tahunan_detail_bulanan|nullable|date_format:Y',
            'tanggal' => 'required_if:jenis_laporan,harian_spesifik|nullable|date_format:Y-m-d',
        ], [
            'bulan.required_if' => 'Format bulan (YYYY-MM) diperlukan.',
            'tahun.required_if' => 'Format tahun (YYYY) diperlukan.',
            'tanggal.required_if' => 'Format tanggal (YYYY-MM-DD) diperlukan.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $jenisLaporan = $request->input('jenis_laporan');
        $responseData = [];
        $startDate = null;
        $endDate = null;
        $periodeJudulUmum = "";

        // --- BAGIAN PERSIAPAN PERIODE (digunakan bersama) ---
        if ($jenisLaporan == 'bulanan_detail_harian') {
            try { 
                $periode = Carbon::parse($request->input('bulan')); 
            } catch (\Exception $e) { 
                return response()->json(['message' => 'Format periode bulan tidak valid.'], 400); 
            }
            $startDate = $periode->copy()->startOfMonth();
            $endDate = $periode->copy()->endOfMonth();
            $periodeJudulUmum = $periode->translatedFormat('F Y');

            // Query untuk chart penjualan bulanan (detail harian)
            $bulan = $periode->month;
            $tahun = $periode->year;
            $jumlahHari = $periode->daysInMonth;

            $penjualanPerHari = Penjualan::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->select(DB::raw('DAY(created_at) as hari_transaksi'), DB::raw('SUM(total_harga) as total_penjualan_per_hari'))
                ->groupBy('hari_transaksi')
                ->orderBy('hari_transaksi', 'asc')
                ->get()
                ->keyBy('hari_transaksi');

            $chartLabelsBulan = []; 
            $chartValuesBulan = [];
            for ($i = 1; $i <= $jumlahHari; $i++) {
                $chartLabelsBulan[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                $chartValuesBulan[] = $penjualanPerHari[$i]->total_penjualan_per_hari ?? 0;
            }
            $responseData['chartData'] = [
                'labels' => $chartLabelsBulan, 
                'values' => $chartValuesBulan, 
                'periodeJudul' => "Grafik Penjualan " . $periodeJudulUmum
            ];

        } elseif ($jenisLaporan == 'tahunan_detail_bulanan') {
            try { 
                $periode = Carbon::parse($request->input('tahun')."-01-01"); 
            } catch (\Exception $e) { 
                return response()->json(['message' => 'Format periode tahun tidak valid.'], 400); 
            }
            $startDate = $periode->copy()->startOfYear();
            $endDate = $periode->copy()->endOfYear();
            $periodeJudulUmum = "Tahun " . $periode->year;

            // Query untuk chart penjualan tahunan (detail bulanan)
            $tahun = $periode->year;
            $penjualanPerBulan = Penjualan::whereYear('created_at', $tahun)
                ->select(DB::raw('MONTH(created_at) as bulan_transaksi'), DB::raw('SUM(total_harga) as total_penjualan_per_bulan'))
                ->groupBy('bulan_transaksi')
                ->orderBy('bulan_transaksi', 'asc')
                ->get()
                ->keyBy('bulan_transaksi');
            
            $chartLabelsTahun = []; 
            $chartValuesTahun = [];
            for ($i = 1; $i <= 12; $i++) {
                $chartLabelsTahun[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                $chartValuesTahun[] = $penjualanPerBulan[$i]->total_penjualan_per_bulan ?? 0;
            }
            $responseData['chartData'] = [
                'labels' => $chartLabelsTahun, 
                'values' => $chartValuesTahun, 
                'periodeJudul' => "Grafik Penjualan " . $periodeJudulUmum
            ];

        } elseif ($jenisLaporan == 'harian_spesifik') {
            try {
                $periode = Carbon::parse($request->input('tanggal'));
            } catch (\Exception $e) {
                return response()->json(['message' => 'Format tanggal tidak valid.'], 400);
            }
            $startDate = $periode->copy()->startOfDay();
            $endDate = $periode->copy()->endOfDay();
            $periodeJudulUmum = $periode->translatedFormat('d F Y');

            // Query untuk total penjualan hari itu
            $totalPendapatanHariIni = Penjualan::whereDate('created_at', $periode)->sum('total_harga');

            // Data untuk chart penjualan utama (harian spesifik)
            $responseData['chartData'] = [
                'labels' => [$periode->translatedFormat('D, d M Y')], // Label bisa tanggal lengkap atau hanya "Total Hari Ini"
                'values' => [$totalPendapatanHariIni],
                'periodeJudul' => "Total Penjualan " . $periodeJudulUmum,
            ];
        }
        // --- AKHIR BAGIAN PERSIAPAN PERIODE & CHART PENJUALAN UTAMA ---

        // Ambil semua penjualan dalam periode untuk tabel detail (Query ini harusnya sudah benar untuk semua kasus)
        $semuaPenjualanPeriode = Penjualan::with('user')
                                    ->whereBetween('created_at', [$startDate, $endDate])
                                    ->orderBy('created_at', 'asc')
                                    ->get();

        $totalPendapatanKeseluruhan = $semuaPenjualanPeriode->sum('total_harga'); // Ini adalah total untuk tabel, bisa beda dengan data chart jika chart diagregasi
        $periodeLaporanTabel = "Detail Transaksi " . $periodeJudulUmum;
        $responseData['tableHtml'] = view('laporan._tabel_penjualan', [
            'penjualan' => $semuaPenjualanPeriode, 
            'totalPendapatan' => $totalPendapatanKeseluruhan, 
            'periode' => $periodeLaporanTabel
        ])->render();

        // --- BAGIAN DATA UNTUK CHART ITEM OBAT TERLARIS (Berlaku untuk semua jenis laporan) ---
        if ($startDate && $endDate) {
            $topItems = DetailPenjualan::whereHas('penjualan', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->join('obat', 'detail_penjualan.id_obat', '=', 'obat.id')
            ->select('obat.nama_obat', DB::raw('SUM(detail_penjualan.jumlah) as total_terjual'))
            ->groupBy('detail_penjualan.id_obat', 'obat.nama_obat')
            ->orderBy('total_terjual', 'desc')
            ->take(10)
            ->get();

            if ($topItems->isNotEmpty()) {
                $topItemsLabels = $topItems->pluck('nama_obat')->toArray();
                $topItemsValues = $topItems->pluck('total_terjual')->toArray();
                $responseData['topItemsChartData'] = [
                    'labels' => $topItemsLabels,
                    'values' => $topItemsValues,
                    'periodeJudul' => "Top 10 Item Terlaris " . $periodeJudulUmum,
                ];
            } else {
                $responseData['topItemsChartData'] = null;
            }
        }
        // --- AKHIR BAGIAN DATA CHART ITEM TERLARIS ---

        return response()->json($responseData);
    }

    public function exportExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_laporan_export' => 'required|in:bulanan_detail_harian_export,tahunan_detail_bulanan_export,harian_spesifik_export',
            'bulan_export' => 'required_if:jenis_laporan_export,bulanan_detail_harian_export|nullable|date_format:Y-m',
            'tahun_export' => 'required_if:jenis_laporan_export,tahunan_detail_bulanan_export|nullable|date_format:Y',
            'tanggal_export' => 'required_if:jenis_laporan_export,harian_spesifik_export|nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            // Redirect kembali dengan error, atau bisa juga handle berbeda untuk ekspor
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $jenisLaporan = $request->input('jenis_laporan_export');
        $startDate = null;
        $endDate = null;
        $periodeLaporanString = "Laporan Penjualan";
        $namaFile = "laporan_penjualan_";

        try {
            if ($jenisLaporan == 'bulanan_detail_harian_export') {
                $periode = Carbon::parse($request->input('bulan_export'));
                $startDate = $periode->copy()->startOfMonth();
                $endDate = $periode->copy()->endOfMonth();
                $periodeLaporanString = $periode->translatedFormat('F Y');
                $namaFile .= $periode->format('Y_m');
            } elseif ($jenisLaporan == 'tahunan_detail_bulanan_export') {
                $periode = Carbon::parse($request->input('tahun_export') . "-01-01");
                $startDate = $periode->copy()->startOfYear();
                $endDate = $periode->copy()->endOfYear();
                $periodeLaporanString = "Tahun " . $periode->year;
                $namaFile .= $periode->format('Y');
            } elseif ($jenisLaporan == 'harian_spesifik_export') {
                $periode = Carbon::parse($request->input('tanggal_export'));
                $startDate = $periode->copy()->startOfDay();
                $endDate = $periode->copy()->endOfDay();
                $periodeLaporanString = $periode->translatedFormat('d F Y');
                $namaFile .= $periode->format('Y_m_d');
            } else {
                return redirect()->back()->with('error_export', 'Jenis laporan untuk ekspor tidak valid.')->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error_export', 'Format periode untuk ekspor tidak valid.')->withInput();
        }

        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error_export', 'Periode ekspor tidak dapat ditentukan.')->withInput();
        }

        $namaFile .= '.xlsx';

        // Proses ekspor
        return Excel::download(new PenjualanExport($startDate, $endDate, $periodeLaporanString), $namaFile);
    }
}