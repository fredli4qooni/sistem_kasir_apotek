<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Penting untuk unique rule saat update
use Carbon\Carbon; // Jangan lupa import Carbon

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Tambahkan Request $request
    {
        $query = Obat::query(); // Mulai dengan query builder

        $filterStok = $request->input('filter_stok');
        $filterKadaluarsa = $request->input('filter_kadaluarsa');
        $searchTerm = $request->input('search'); // Untuk pencarian

        if ($filterStok === 'menipis') {
            $query->whereRaw('stok <= stok_minimal');
        }

        if ($filterKadaluarsa === 'urgent') {
            // Mendekati expired (misal, 30 hari) atau sudah expired
            $tanggalCekExpired = Carbon::now()->addDays(30);
            $query->where('tanggal_kadaluarsa', '<=', $tanggalCekExpired);
        } elseif ($filterKadaluarsa === 'sudah_expired') {
            $query->where('tanggal_kadaluarsa', '<', Carbon::now()->toDateString());
        }

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_obat', 'like', "%{$searchTerm}%")
                    ->orWhere('kode_obat', 'like', "%{$searchTerm}%");
            });
        }

        $obats = $query->orderBy('nama_obat', 'asc')->paginate(10)->appends($request->except('page'));
        // appends($request->except('page')) agar filter tetap aktif saat paginasi

        return view('obat.index', compact('obats', 'filterStok', 'filterKadaluarsa', 'searchTerm'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_obat' => 'required|string|max:20|unique:obat,kode_obat',
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'distributor' => 'nullable|string|max:255',
            'nomor_batch' => 'nullable|string|max:100', 
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli', // harga jual harus >= harga beli
            'stok' => 'required|integer|min:0',
            'stok_minimal' => 'nullable|integer|min:0',
            'tanggal_kadaluarsa' => 'required|date|after_or_equal:today',
        ], [
            'kode_obat.unique' => 'Kode obat ini sudah terdaftar.',
            'harga_jual.gte' => 'Harga jual tidak boleh lebih kecil dari harga beli.',
            'tanggal_kadaluarsa.after_or_equal' => 'Tanggal kadaluarsa minimal hari ini.'
        ]);

        Obat::create($validatedData);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Obat $obat) // Route Model Binding
    {
        // Biasanya untuk API atau jika ada halaman detail khusus.
        // Untuk CRUD standar web, seringkali tidak digunakan karena detail sudah ada di edit atau index.
        // return view('obat.show', compact('obat'));
        return redirect()->route('obat.edit', $obat->id); // Atau langsung redirect ke edit
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Obat $obat) // Route Model Binding
    {
        return view('obat.edit', compact('obat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Obat $obat) // Route Model Binding
    {
        $validatedData = $request->validate([
            'kode_obat' => [
                'required',
                'string',
                'max:20',
                Rule::unique('obat', 'kode_obat')->ignore($obat->id), // Abaikan ID saat ini untuk unique check
            ],
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'distributor' => 'nullable|string|max:255',        
            'nomor_batch' => 'nullable|string|max:100',  
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'stok' => 'required|integer|min:0',
            'stok_minimal' => 'nullable|integer|min:0',
            'tanggal_kadaluarsa' => 'required|date', // Tidak perlu after_or_equal:today jika hanya edit tanggal yang sudah lewat
        ], [
            'kode_obat.unique' => 'Kode obat ini sudah terdaftar untuk item lain.',
            'harga_jual.gte' => 'Harga jual tidak boleh lebih kecil dari harga beli.',
        ]);

        $obat->update($validatedData);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Obat $obat) // Route Model Binding
    {
        try {
            $obat->delete();
            return redirect()->route('obat.index')->with('success', 'Data obat berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani jika ada constraint database (misal obat sudah terpakai di transaksi)
            // Kode error 1451 (MySQL) biasanya untuk foreign key constraint violation
            if ($e->errorInfo[1] == 1451) {
                return redirect()->route('obat.index')->with('error', 'Gagal menghapus obat. Obat ini mungkin sudah terkait dengan transaksi.');
            }
            return redirect()->route('obat.index')->with('error', 'Gagal menghapus obat. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return response()->json([]);
        }

        $obats = Obat::where(function ($q) use ($query) {
            $q->where('nama_obat', 'LIKE', "%{$query}%")
                ->orWhere('kode_obat', 'LIKE', "%{$query}%");
        })
            // ->where('stok', '>', 0) // Hanya tampilkan yang stoknya ada (opsional, bisa dihandle di frontend)
            ->select('id', 'kode_obat', 'nama_obat', 'harga_jual', 'stok') // Pilih kolom yang dibutuhkan saja
            ->take(10) // Batasi hasil
            ->get();

        return response()->json($obats);
    }
}
