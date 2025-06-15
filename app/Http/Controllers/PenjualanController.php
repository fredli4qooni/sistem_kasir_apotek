<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Untuk transaksi database
use Carbon\Carbon;
use Illuminate\Validation\ValidationException; // Untuk melempar error validasi manual

class PenjualanController extends Controller
{
    public function create()
    {
        // Generate nomor transaksi otomatis
        $today = Carbon::today();
        // Ambil transaksi terakhir hari ini untuk nomor urut berdasarkan ID (lebih aman dari count jika ada delete)
        $lastTransactionToday = Penjualan::whereDate('created_at', $today)
                                          ->orderBy('id', 'desc') // Urutkan berdasarkan ID, bukan hanya created_at
                                          ->first();

        $nomorUrut = 1;
        if ($lastTransactionToday) {
            // Ambil nomor urut dari nomor transaksi terakhir
            // Asumsi format INV/YYYYMM/NNN
            $parts = explode('/', $lastTransactionToday->nomor_transaksi);
            if (count($parts) === 3) {
                $nomorUrut = intval($parts[2]) + 1;
            } else {
                // Fallback jika format tidak sesuai, atau handle error
                $nomorUrut = Penjualan::whereDate('created_at', $today)->count() + 1;
            }
        }

        $nomorTransaksi = 'INV/' . $today->format('Ym') . '/' . sprintf('%03d', $nomorUrut);

        return view('transaksi.create', compact('nomorTransaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_transaksi' => 'required|string|unique:penjualan,nomor_transaksi',
            'id_user' => 'required|exists:users,id',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1', // Pastikan ada item
            'items.*.id_obat' => 'required|exists:obat,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.diskon_item_persen' => 'nullable|numeric|min:0|max:100', // Validasi diskon
            'items.*.diskon_item_nominal' => 'nullable|numeric|min:0',        // Validasi diskon
        ], [
            'nomor_transaksi.unique' => 'Nomor transaksi sudah ada. Harap refresh halaman.',
            'items.required' => 'Keranjang belanja tidak boleh kosong.',
            'items.min' => 'Keranjang belanja tidak boleh kosong.',
            'items.*.id_obat.required' => 'Item obat tidak valid.',
            'items.*.id_obat.exists' => 'Item obat tidak ditemukan di database.',
            'items.*.jumlah.required' => 'Jumlah item obat tidak valid.',
            'items.*.jumlah.min' => 'Jumlah item obat minimal 1.',
            'items.*.harga_satuan.required' => 'Harga satuan item obat tidak valid.',
            'items.*.diskon_item_persen.min' => 'Diskon persen item tidak valid.',
            'items.*.diskon_item_persen.max' => 'Diskon persen item maksimal 100%.',
            'items.*.diskon_item_nominal.min' => 'Diskon nominal item tidak valid.',
        ]);

        // Validasi tambahan: jumlah bayar harus cukup (total_harga dari frontend masih sebelum diskon server-side)
        // Kita akan hitung ulang total_harga sebenarnya di server.
        // Untuk validasi bayar cukup, kita bisa hitung total sementara dari request
        $totalHargaDariRequestSetelahDiskonFrontend = 0;
        foreach($request->items as $itemDataFE) {
            $hargaAwalItemFE = $itemDataFE['jumlah'] * $itemDataFE['harga_satuan'];
            $diskonPersenFE = $itemDataFE['diskon_item_persen'] ?? 0;
            $diskonNominalFE = $itemDataFE['diskon_item_nominal'] ?? 0;
            $diskonDariPersenFE = ($diskonPersenFE / 100) * $hargaAwalItemFE;
            $diskonFinalItemFE = $diskonNominalFE > 0 ? $diskonNominalFE : $diskonDariPersenFE;
            if ($diskonFinalItemFE > $hargaAwalItemFE) $diskonFinalItemFE = $hargaAwalItemFE;
            $totalHargaDariRequestSetelahDiskonFrontend += ($hargaAwalItemFE - $diskonFinalItemFE);
        }

        if ($request->jumlah_bayar < $totalHargaDariRequestSetelahDiskonFrontend) {
            throw ValidationException::withMessages(['jumlah_bayar' => 'Jumlah bayar kurang dari total harga setelah diskon.']);
        }

        DB::beginTransaction(); // Mulai transaksi database

        try {
            // Simpan header Penjualan dulu untuk dapat ID
            $penjualan = Penjualan::create([
                'nomor_transaksi' => $request->nomor_transaksi,
                'id_user' => $request->id_user,
                'total_harga' => 0, // Placeholder, akan diupdate
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => 0, // Placeholder
                'catatan' => $request->catatan,
            ]);

            $grandTotalPenjualanServer = 0;
            $detailsToCreate = [];
            $itemDetailsForStruk = [];

            foreach ($request->items as $itemData) {
                $obat = Obat::find($itemData['id_obat']);
                
                // Validasi stok
                if (!$obat || $obat->stok < $itemData['jumlah']) {
                    DB::rollBack(); // Batalkan transaksi
                    // Kirim error spesifik obat mana yang stoknya kurang
                    throw ValidationException::withMessages(['stok_error' => 'Stok untuk obat "' . ($obat ? $obat->nama_obat : 'Tidak Dikenal') . '" tidak mencukupi. Sisa stok: ' . ($obat ? $obat->stok : '0') . '.']);
                }

                $hargaSatuan = $itemData['harga_satuan'];
                $jumlah = $itemData['jumlah'];
                $hargaTotalItemSebelumDiskon = $jumlah * $hargaSatuan;

                $diskonPersen = $itemData['diskon_item_persen'] ?? 0;
                $diskonNominalInput = $itemData['diskon_item_nominal'] ?? 0;
                
                // Hitung diskon efektif di backend
                $diskonDariPersenEfektif = ($diskonPersen / 100) * $hargaTotalItemSebelumDiskon;
                $diskonFinalItemEfektif = $diskonNominalInput > 0 ? $diskonNominalInput : $diskonDariPersenEfektif;
                // Pastikan diskon tidak melebihi harga total item
                if ($diskonFinalItemEfektif > $hargaTotalItemSebelumDiskon) {
                    $diskonFinalItemEfektif = $hargaTotalItemSebelumDiskon;
                }

                $subTotalSetelahDiskonBackend = $hargaTotalItemSebelumDiskon - $diskonFinalItemEfektif;
                $grandTotalPenjualanServer += $subTotalSetelahDiskonBackend;

                $detailsToCreate[] = [
                    'id_obat' => $itemData['id_obat'],
                    'jumlah' => $jumlah,
                    'harga_satuan_saat_transaksi' => $hargaSatuan,
                    'diskon_item_persen' => $diskonPersen,
                    'diskon_item_nominal' => $diskonNominalInput,
                    'sub_total' => $subTotalSetelahDiskonBackend,
                ];
                
                // Update stok obat
                $obat->decrement('stok', $jumlah);

                $itemDetailsForStruk[] = [
                    'nama_obat' => $obat->nama_obat,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'diskon_item_persen' => $diskonPersen,
                    'diskon_item_nominal' => $diskonNominalInput,
                    'sub_total_display' => $subTotalSetelahDiskonBackend,
                ];
            }

            // Simpan semua detail penjualan terkait
            $penjualan->detailPenjualan()->createMany($detailsToCreate);

            // Update total_harga dan kembalian di header penjualan
            $penjualan->total_harga = $grandTotalPenjualanServer;
            $penjualan->kembalian = $request->jumlah_bayar - $grandTotalPenjualanServer;
            
            if ($penjualan->kembalian < 0) { // Double check
                DB::rollBack();
                throw ValidationException::withMessages(['jumlah_bayar' => 'Terjadi kesalahan perhitungan kembalian. Jumlah bayar kurang.']);
            }
            
            $penjualan->save();

            DB::commit(); // Semua berhasil, commit transaksi

            session()->flash('struk_data', [
                'penjualan' => $penjualan->refresh(), // Refresh untuk data terbaru
                'items' => $itemDetailsForStruk,
                'kasir' => Auth::user()->name,
            ]);
            
            return redirect()->route('transaksi.struk', ['id_penjualan' => $penjualan->id])
                             ->with('success', 'Transaksi berhasil disimpan.');

        } catch (ValidationException $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error validasi
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error lain
            // Log error: Log::error($e->getMessage());
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage())
                             ->withInput();
        }
    }

    // Metode showStruk akan kita buat di langkah selanjutnya
    public function showStruk($id_penjualan)
    {
        // Cek apakah ada data struk di session (dari proses store)
        if (session()->has('struk_data')) {
            $data = session('struk_data');
            // Pastikan id_penjualan dari session cocok dengan id dari URL
            if ($data['penjualan']->id == $id_penjualan) {
                 return view('transaksi.struk', $data);
            }
        }

        // Jika tidak ada di session atau ID tidak cocok, query ulang dari database
        $penjualan = Penjualan::with(['detailPenjualan.obat', 'user'])->find($id_penjualan);

        if (!$penjualan) {
            return redirect()->route('transaksi.create')->with('error', 'Data transaksi tidak ditemukan.');
        }

        // Format data items untuk view struk (jika diambil dari DB)
        $itemsForStruk = $penjualan->detailPenjualan->map(function ($detail) {
            return [
                'nama_obat' => $detail->obat->nama_obat,
                'jumlah' => $detail->jumlah,
                'harga_satuan' => $detail->harga_satuan_saat_transaksi,
                'diskon_item_persen' => $detail->diskon_item_persen ?? 0,
                'diskon_item_nominal' => $detail->diskon_item_nominal ?? 0,
                'sub_total_display' => $detail->sub_total,
            ];
        })->all();

        return view('transaksi.struk', [
            'penjualan' => $penjualan,
            'items' => $itemsForStruk,
            'kasir' => $penjualan->user->name, // Ambil nama kasir dari relasi
        ]);
    }
}