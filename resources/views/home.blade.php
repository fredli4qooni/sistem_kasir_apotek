@extends('layouts.app')

@push('styles')
    <style>
        .dashboard-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 1rem;
            border-radius: .5rem;
            margin-bottom: 2rem;
        }

        .dashboard-hero h2 {
            font-weight: 300;
        }

        .stat-card {
            transition: transform .2s ease-out, box-shadow .2s ease-out;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .icon-circle-primary {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            color: var(--bs-primary);
        }

        .icon-circle-success {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: var(--bs-success);
        }

        .icon-circle-info {
            background-color: rgba(var(--bs-info-rgb), 0.1);
            color: var(--bs-info);
        }

        .icon-circle-warning {
            background-color: rgba(var(--bs-warning-rgb), 0.1);
            color: var(--bs-warning);
        }

        .icon-circle-danger {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: var(--bs-danger);
        }

        .card-link-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            /* Pastikan di atas konten card, tapi di bawah elemen interaktif lain jika ada */
        }

        .list-group-item-action:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        <div class="dashboard-hero text-center shadow-sm">
            <h2 class="display-5">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="lead">Ini adalah ringkasan aktivitas Anda di Sistem Kasir Apotek Berkah Ibu.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- ======================== PEMILIK DASHBOARD ======================== --}}
        @role('pemilik')
            <div class="row gy-4">
                {{-- KARTU STATISTIK --}}
                <div class="col-md-4 col-sm-6">
                    <div class="card stat-card shadow-sm h-100 text-center border-0">
                        <div class="card-body">
                            <div class="icon-circle icon-circle-primary mx-auto">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <h6 class="text-muted card-subtitle mb-1">Pendapatan Hari Ini</h6>
                            <h3 class="card-title fw-bold mb-0">Rp
                                {{ number_format($totalPendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
                            <small class="text-muted">({{ $jumlahTransaksiHariIni ?? 0 }} Transaksi)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card stat-card shadow-sm h-100 text-center border-0">
                        <div class="card-body">
                            <div class="icon-circle icon-circle-success mx-auto">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <h6 class="text-muted card-subtitle mb-1">Pendapatan Bulan Ini</h6>
                            <h3 class="card-title fw-bold mb-0">Rp
                                {{ number_format($totalPendapatanBulanIni ?? 0, 0, ',', '.') }}</h3>
                            <small class="text-muted">({{ $jumlahTransaksiBulanIni ?? 0 }} Transaksi)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('laporan.penjualan.index') }}" class="text-decoration-none">
                        <div class="card stat-card shadow-sm h-100 text-center border-0 bg-primary text-white">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                <h5 class="card-title">Laporan Lengkap</h5>
                                <p class="small mb-0">Akses detail laporan penjualan</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- GRAFIK PENJUALAN 7 HARI --}}
                <div class="col-md-7">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom-0 pt-3">
                            <h5 class="card-title mb-0 text-primary"><i class="fas fa-chart-line me-2"></i>Tren Penjualan 7 Hari
                                Terakhir</h5>
                        </div>
                        <div class="card-body">
                            @if (!empty($penjualan7HariLabels) && !empty($penjualan7HariValues) && count(array_filter($penjualan7HariValues)) > 0)
                                <canvas id="pemilikSalesChart" style="min-height: 250px; max-height:300px;"></canvas>
                            @else
                                <p class="text-center text-muted mt-4 pt-4">Belum ada data penjualan yang cukup untuk
                                    menampilkan grafik.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PRODUK TERLARIS BULAN INI --}}
                <div class="col-md-5">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom-0 pt-3">
                            <h5 class="card-title mb-0 text-info"><i class="fas fa-award me-2"></i>Produk Terlaris Bulan Ini
                            </h5>
                        </div>
                        <div class="card-body p-0"> {{-- p-0 agar list-group flush dengan card --}}
                            @if (isset($produkTerlarisBulanIni) && $produkTerlarisBulanIni->isNotEmpty())
                                <ul class="list-group list-group-flush">
                                    @foreach ($produkTerlarisBulanIni as $produk)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                <i
                                                    class="fas fa-capsules text-muted me-2"></i>{{ Str::limit($produk->nama_obat, 30) }}
                                            </span>
                                            <span class="badge bg-info rounded-pill">{{ $produk->total_terjual }} unit</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-center text-muted p-4">Belum ada data produk terlaris.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endrole


        {{-- ======================== KASIR DASHBOARD ======================== --}}
        @role('kasir')
            <div class="row gy-4">
                {{-- SHORTCUT UTAMA --}}
                <div class="col-md-4">
                    <a href="{{ route('transaksi.create') }}" class="text-decoration-none">
                        <div class="card stat-card shadow-sm h-100 text-white text-center" style="background-color: #20c997;">
                            {{-- Teal color --}}
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <div class="icon-circle bg-white text-success mx-auto"
                                    style="width:60px; height:60px; font-size:1.8rem;">
                                    <i class="fas fa-cash-register"></i>
                                </div>
                                <h4 class="card-title mt-3">Kasir</h4>
                                <p class="small mb-0">Mulai Transaksi Baru</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('obat.index') }}" class="text-decoration-none">
                        <div class="card stat-card shadow-sm h-100 text-white text-center" style="background-color: #0dcaf0;">
                            {{-- Cyan color --}}
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <div class="icon-circle bg-white text-info mx-auto"
                                    style="width:60px; height:60px; font-size:1.8rem;">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <h4 class="card-title mt-3">Manajemen Obat</h4>
                                <p class="small mb-0">Kelola Data Obat</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('laporan.penjualan.index') }}" class="text-decoration-none">
                        <div class="card stat-card shadow-sm h-100 text-white text-center" style="background-color: #fd7e14;">
                            {{-- Orange color --}}
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <div class="icon-circle bg-white text-warning mx-auto"
                                    style="width:60px; height:60px; font-size:1.8rem;">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <h4 class="card-title mt-3">Laporan</h4>
                                <p class="small mb-0">Lihat Laporan Penjualan</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- PERINGATAN STOK --}}
                @if (isset($obatStokMenipis) && $obatStokMenipis->isNotEmpty())
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-header bg-warning text-dark d-flex align-items-center">
                                <div class="icon-circle icon-circle-warning me-2"
                                    style="width:30px; height:30px; font-size:1rem; margin-bottom:0;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h5 class="mb-0">Stok Menipis</h5>
                            </div>
                            <ul class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                                @foreach ($obatStokMenipis as $obat)
                                    <a href="{{ route('obat.edit', $obat->id) }}"
                                        class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $obat->nama_obat }}</h6>
                                            <small class="text-muted">Sisa: {{ $obat->stok }}</small>
                                        </div>
                                        <small class="text-muted">Kode: {{ $obat->kode_obat }} (Min:
                                            {{ $obat->stok_minimal }})</small>
                                    </a>
                                @endforeach
                            </ul>
                            <div class="card-footer bg-white text-end py-2">
                                <a href="{{ route('obat.index', ['filter_stok' => 'menipis']) }}"
                                    class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PERINGATAN KADALUARSA --}}
                @if (isset($obatAkanExpired) && $obatAkanExpired->isNotEmpty())
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-header bg-danger text-white d-flex align-items-center">
                                <div class="icon-circle bg-white text-danger me-2"
                                    style="width:30px; height:30px; font-size:1rem; margin-bottom:0;">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <h5 class="mb-0">Segera/Sudah Kadaluarsa</h5>
                            </div>
                            <ul class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                                @foreach ($obatAkanExpired as $obat)
                                    <a href="{{ route('obat.edit', $obat->id) }}"
                                        class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $obat->nama_obat }}</h6>
                                            <small
                                                class="{{ $obat->tanggal_kadaluarsa->isPast() ? 'text-danger fw-bold' : 'text-warning' }}">
                                                {{ $obat->tanggal_kadaluarsa->format('d M Y') }}
                                            </small>
                                        </div>
                                        @php
                                            $statusKadaluarsa = '';
                                            $selisihHari = $obat->tanggal_kadaluarsa->diffInDays(now(), false);
                                            if ($selisihHari < 0) {
                                                $statusKadaluarsa = 'Expired ' . abs($selisihHari) . ' hari lalu';
                                            } elseif ($selisihHari == 0) {
                                                $statusKadaluarsa = 'Expired Hari Ini!';
                                            } else {
                                                $statusKadaluarsa = 'Expired dalam ' . $selisihHari . ' hari';
                                            }
                                        @endphp
                                        <small class="text-muted">Status: {{ $statusKadaluarsa }}</small>
                                    </a>
                                @endforeach
                            </ul>
                            <div class="card-footer bg-white text-end py-2">
                                <a href="{{ route('obat.index', ['filter_kadaluarsa' => 'urgent']) }}"
                                    class="btn btn-sm btn-outline-danger">Lihat Semua</a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Transaksi Terakhir Kasir --}}
                @if (isset($transaksiTerakhirKasir) && $transaksiTerakhirKasir->isNotEmpty())
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light d-flex align-items-center">
                                <div class="icon-circle icon-circle-info me-2"
                                    style="width:30px; height:30px; font-size:1rem; margin-bottom:0;">
                                    <i class="fas fa-history"></i>
                                </div>
                                <h5 class="mb-0">Aktivitas Transaksi Terakhir Anda</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>No. Transaksi</th>
                                            <th>Tanggal</th>
                                            <th class="text-end">Total (Rp)</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksiTerakhirKasir as $trx)
                                            <tr>
                                                <td>{{ $trx->nomor_transaksi }}</td>
                                                <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                                <td class="text-end">{{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('transaksi.struk', $trx->id) }}"
                                                        class="btn btn-xs btn-outline-info" title="Lihat Struk">
                                                        <i class="fas fa-receipt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif


                @if (
                    (!isset($obatStokMenipis) || $obatStokMenipis->isEmpty()) &&
                        (!isset($obatAkanExpired) || $obatAkanExpired->isEmpty()))
                    <div class="col-md-12">
                        <div class="alert alert-success mt-3 d-flex align-items-center" role="alert">
                            <div class="icon-circle icon-circle-success me-3"
                                style="width:40px; height:40px; font-size:1.2rem; margin-bottom:0;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                Semua aman! Tidak ada peringatan stok kritis atau obat mendekati kadaluarsa.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endrole
    </div>
@endsection

@push('scripts')
    {{-- Script untuk grafik Pemilik sama seperti sebelumnya --}}
    @role('pemilik')
        @if (!empty($penjualan7HariLabels) && !empty($penjualan7HariValues) && count(array_filter($penjualan7HariValues)) > 0)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const pemilikSalesChartCanvas = document.getElementById('pemilikSalesChart');
                    if (pemilikSalesChartCanvas) {
                        const ctxPemilik = pemilikSalesChartCanvas.getContext('2d');
                        new Chart(ctxPemilik, {
                            type: 'line',
                            data: {
                                labels: @json($penjualan7HariLabels),
                                datasets: [{
                                    label: 'Penjualan (Rp)',
                                    data: @json($penjualan7HariValues),
                                    borderColor: '#667eea',
                                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                    fill: true,
                                    tension: 0.3,
                                    pointBackgroundColor: '#667eea',
                                    pointBorderColor: '#fff',
                                    pointHoverRadius: 7,
                                    pointRadius: 5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                            }
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: '#fff',
                                        titleColor: '#555',
                                        bodyColor: '#555',
                                        borderColor: '#ddd',
                                        borderWidth: 1,
                                        callbacks: {
                                            label: function(context) {
                                                return (context.dataset.label || '') + ': Rp ' + new Intl
                                                    .NumberFormat('id-ID').format(context.parsed.y);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endrole
@endpush
