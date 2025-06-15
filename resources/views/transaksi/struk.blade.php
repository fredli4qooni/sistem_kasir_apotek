{{-- resources/views/transaksi/struk.blade.php --}}
@extends('layouts.app') {{-- Atau layout polos jika ingin cetak lebih bersih --}}

@section('content')
    <div class="container mt-4 mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="mb-1">Apotek Berkah Ibu</h3>
                            <p class="mb-0">Jl. Sehat Selalu No. 1, Kota Bahagia</p>
                            <p class="mb-0">Telp: (021) 123-4567</p>
                            <hr class="my-2">
                            <h4 class="mb-0">STRUK PEMBELIAN</h4>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success d-print-none" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table table-sm table-borderless mb-2">
                            <tr>
                                <td style="width: 30%;">No. Transaksi</td>
                                <td style="width: 2%;">:</td>
                                <td><strong>{{ $penjualan->nomor_transaksi }}</strong></td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td>{{ $penjualan->created_at->format('d M Y, H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td>Kasir</td>
                                <td>:</td>
                                <td>{{ $kasir ?? $penjualan->user->name }}</td> {{-- Ambil dari session atau relasi --}}
                            </tr>
                        </table>

                        <hr class="my-2">

                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Diskon</th> {{-- Kolom Diskon --}}
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item) {{-- $items dari struk_data --}}
                                    @php
                                        $hargaAwalItem = $item['jumlah'] * $item['harga_satuan'];
                                        $diskonPersen = $item['diskon_item_persen'] ?? 0;
                                        $diskonNominal = $item['diskon_item_nominal'] ?? 0;
                                        $displayDiskonItem = "Rp 0";
                                        if ($diskonNominal > 0) {
                                            $displayDiskonItem = "Rp " . number_format($diskonNominal, 0, ',', '.');
                                        } elseif ($diskonPersen > 0) {
                                            $nilaiDiskonPersen = ($diskonPersen / 100) * $hargaAwalItem;
                                            $displayDiskonItem = number_format($diskonPersen,0) . "% (Rp " . number_format($nilaiDiskonPersen, 0, ',', '.') . ")";
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $item['nama_obat'] }}</td>
                                        <td class="text-center">{{ $item['jumlah'] }}</td>
                                        <td class="text-end">Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ $displayDiskonItem }}</td>
                                        <td class="text-end">Rp {{ number_format($item['sub_total_display'] ?? $item['sub_total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr class="my-2">

                        <table class="table table-sm table-borderless">
                            <tr>
                                <td style="width: 60%;" class="text-end"><strong>TOTAL</strong></td>
                                <td class="text-end"><strong>Rp
                                        {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-end">Bayar</td>
                                <td class="text-end">Rp {{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">Kembalian</td>
                                <td class="text-end">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
                            </tr>
                        </table>

                        @if ($penjualan->catatan)
                            <div class="mt-2">
                                <small><strong>Catatan:</strong> {{ $penjualan->catatan }}</small>
                            </div>
                        @endif

                        <hr class="my-2">
                        <div class="text-center mt-3">
                            <p class="mb-0">Terima kasih atas kunjungan Anda!</p>
                            <p class="fst-italic"><small>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</small>
                            </p>
                        </div>

                        <div class="mt-4 text-center d-print-none">
                            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Transaksi Baru
                            </a>
                            <button onclick="window.print()" class="btn btn-success ms-2">
                                <i class="fas fa-print"></i> Cetak Struk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tambahkan CSS untuk print jika diperlukan --}}
    <style media="print">
        body {
            margin: 0;
            padding: 0;
            background-color: #FFF;
            /* Atur background agar tidak gelap saat print dari dark mode (jika ada) */
        }

        .container {
            width: 100% !important;
            /* Gunakan lebar penuh saat print */
            margin: 0 !important;
            padding: 0 !important;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
        }

        .d-print-none {
            display: none !important;
            /* Sembunyikan elemen yang tidak perlu dicetak */
        }

        @page {
            size: 80mm auto;
            /* Ukuran kertas thermal printer umum, atau sesuaikan */
            margin: 5mm;
        }

        /* Atur font size agar sesuai untuk struk kecil */
        body,
        table,
        p,
        h3,
        h4,
        small {
            font-size: 9pt !important;
            /* Sesuaikan ukuran font */
        }

        h3 {
            font-size: 12pt !important;
            font-weight: bold;
        }

        h4 {
            font-size: 10pt !important;
            font-weight: bold;
        }

        hr {
            border-top: 1px dashed #000 !important;
            /* Garis putus-putus untuk pemisah */
        }

        /* Adjust table layout for print with discount column */
        @media print {
            table th,
            table td {
                font-size: 8pt !important;
                padding: 1px 2px !important;
            }
            
            /* Make discount column narrower */
            table th:nth-child(4),
            table td:nth-child(4) {
                width: 15% !important;
            }
        }
    </style>
@endsection