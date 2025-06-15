@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Manajemen Data Obat') }}</span>
                    <a href="{{ route('obat.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Obat Baru
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form Filter --}}
                    <form method="GET" action="{{ route('obat.index') }}" class="mb-3">
                        <div class="row gy-2">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Nama/Kode Obat..." value="{{ $searchTerm ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <select name="filter_stok" class="form-select form-select-sm">
                                    <option value="">Semua Status Stok</option>
                                    <option value="menipis" {{ ($filterStok ?? '') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="filter_kadaluarsa" class="form-select form-select-sm">
                                    <option value="">Semua Status Kadaluarsa</option>
                                    <option value="urgent" {{ ($filterKadaluarsa ?? '') == 'urgent' ? 'selected' : '' }}>Segera/Sudah Expired</option>
                                    <option value="sudah_expired" {{ ($filterKadaluarsa ?? '') == 'sudah_expired' ? 'selected' : '' }}>Sudah Expired</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="filter_distributor" class="form-control form-control-sm" placeholder="Distributor..." value="{{ $filterDistributor ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info btn-sm w-100">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                @if(($searchTerm ?? '') || ($filterStok ?? '') || ($filterKadaluarsa ?? '') || ($filterDistributor ?? ''))
                                    <a href="{{ route('obat.index') }}" class="btn btn-secondary btn-sm w-100 mt-1">
                                        <i class="fas fa-refresh"></i> Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    {{-- Akhir Form Filter --}}
                    
                    {{-- Info Summary --}}
                    @if(isset($totalObat))
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    Total: <strong>{{ $totalObat ?? $obats->total() }}</strong> obat
                                    @if(isset($stokMenipis) && $stokMenipis > 0)
                                        | <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> {{ $stokMenipis }} stok menipis</span>
                                    @endif
                                    @if(isset($obatExpired) && $obatExpired > 0)
                                        | <span class="text-danger"><i class="fas fa-times-circle"></i> {{ $obatExpired }} expired</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm"> {{-- Tambahkan table-sm untuk lebih ringkas --}}
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 100px;">Kode</th>
                                    <th>Nama Obat</th>
                                    <th style="width: 120px;">Distributor</th> {{-- Tambah Kolom --}}
                                    <th style="width: 110px;">No. Batch</th>  {{-- Tambah Kolom --}}
                                    <th style="width: 80px;">Satuan</th>
                                    <th style="width: 110px;" class="text-end">Harga Jual</th>
                                    <th style="width: 70px;" class="text-center">Stok</th>
                                    <th style="width: 70px;" class="text-center">Min.Stok</th>
                                    <th style="width: 120px;">Kadaluarsa</th>
                                    <th style="width: 100px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($obats as $key => $item)
                                    <tr class="{{ $item->stok <= $item->stok_minimal ? 'table-warning' : '' }} {{ $item->tanggal_kadaluarsa->isPast() ? 'table-danger fw-bold opacity-75' : ($item->tanggal_kadaluarsa->diffInDays(now()) <= 30 ? 'table-warning opacity-90' : '') }}">
                                        <td>{{ $obats->firstItem() + $key }}</td>
                                        <td><small class="text-muted">{{ $item->kode_obat }}</small></td>
                                        <td>
                                            <strong>{{ $item->nama_obat }}</strong>
                                            @if($item->deskripsi)
                                                <br><small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $item->distributor ?? '-' }}</small> {{-- Tampilkan Data --}}
                                        </td>
                                        <td>
                                            <small>{{ $item->nomor_batch ?? '-' }}</small> {{-- Tampilkan Data --}}
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $item->satuan }}</span></td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</strong>
                                            @if($item->harga_beli)
                                                <br><small class="text-muted">Beli: Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $item->stok <= $item->stok_minimal ? 'bg-danger' : ($item->stok <= $item->stok_minimal * 2 ? 'bg-warning text-dark' : 'bg-success') }}">
                                                {{ $item->stok }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $item->stok_minimal }}</td>
                                        <td>
                                            <small>{{ $item->tanggal_kadaluarsa->format('d M Y') }}</small>
                                            @if ($item->tanggal_kadaluarsa->isPast())
                                                <br><span class="badge bg-dark">Expired</span>
                                            @elseif ($item->tanggal_kadaluarsa->diffInDays(now()) <= 30 && $item->tanggal_kadaluarsa >= now()->startOfDay())
                                                <br><span class="badge bg-danger">Hampir Exp</span>
                                            @elseif ($item->tanggal_kadaluarsa->diffInDays(now()) <= 60)
                                                <br><span class="badge bg-warning text-dark">{{ $item->tanggal_kadaluarsa->diffInDays(now()) }}h lagi</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('obat.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('obat.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('obat.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus {{ $item->nama_obat }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4"> {{-- Sesuaikan colspan --}}
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>Tidak ada data obat yang ditemukan.</p>
                                                @if(($searchTerm ?? '') || ($filterStok ?? '') || ($filterKadaluarsa ?? '') || ($filterDistributor ?? ''))
                                                    <a href="{{ route('obat.index') }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-refresh"></i> Reset Filter
                                                    </a>
                                                @else
                                                    <a href="{{ route('obat.create') }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-plus"></i> Tambah Obat Pertama
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    @if($obats->hasPages())
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Menampilkan {{ $obats->firstItem() }} sampai {{ $obats->lastItem() }} dari {{ $obats->total() }} data
                            </div>
                            <div>
                                {{ $obats->appends(request()->query())->links() }} {{-- Mempertahankan parameter filter --}}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom CSS --}}
@push('styles')
<style>
    .btn-group .btn {
        border-radius: 0;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    .table-sm td {
        padding: 0.3rem;
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }
</style>
@endpush

{{-- Font Awesome (jika belum ada di layout utama) --}}
{{-- Uncomment jika Font Awesome belum di-setup --}}
{{-- 
@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
--}}

@endsection