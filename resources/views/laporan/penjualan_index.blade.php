{{-- resources/views/laporan/penjualan_index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container" id="laporanPageContainer"> {{-- Beri ID pada container utama --}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span id="judulLaporanUtama">{{ __('Laporan Penjualan') }}</span>
                        <button type="button" class="btn btn-secondary btn-sm d-print-none" id="btnCetakLaporanKeseluruhan">
                            {{-- d-print-none agar tombol ini tidak ikut tercetak --}}
                            <i class="fas fa-print"></i> Cetak Laporan Ini
                        </button>
                    </div>

                    <div class="card-body">
                        <form id="formFilterLaporan" class="mb-4 d-print-none"> {{-- d-print-none untuk form filter --}}
                            <div class="row align-items-end gy-3">
                                {{-- Filter untuk Laporan Bulanan Detail Harian --}}
                                <div class="col-md-3 col-sm-6 border-end">
                                    <label class="form-label fw-bold">Laporan Bulanan</label>
                                    <div class="mb-2">
                                        <label for="bulan_filter_bulanan" class="form-label small">Pilih Bulan</label>
                                        <select name="bulan_filter_bulanan" id="bulan_filter_bulanan"
                                            class="form-select form-select-sm">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ date('m') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="tahun_filter_bulanan" class="form-label small">Pilih Tahun</label>
                                        <select name="tahun_filter_bulanan" id="tahun_filter_bulanan"
                                            class="form-select form-select-sm">
                                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="button" id="btnLihatBulanan" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-calendar-alt"></i> Lihat Laporan Bulanan
                                    </button>
                                </div>

                                {{-- Filter untuk Laporan Harian Spesifik --}}
                                <div class="col-md-3 col-sm-6 border-end">
                                    <label class="form-label fw-bold">Laporan Harian</label>
                                    <div class="mb-2">
                                        <label for="tanggal_filter_harian" class="form-label small">Pilih Tanggal</label>
                                        <input type="date" name="tanggal_filter_harian" id="tanggal_filter_harian"
                                            class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <button type="button" id="btnLihatHarian" class="btn btn-info btn-sm w-100 mt-4">
                                        {{-- mt-4 untuk align tombol --}}
                                        <i class="fas fa-calendar-day"></i> Lihat Laporan Harian
                                    </button>
                                </div>

                                {{-- Tombol Laporan Cepat --}}
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label fw-bold">Laporan Cepat</label>
                                    <div class="d-grid gap-2"> {{-- d-grid untuk tombol memenuhi lebar --}}
                                        <button type="button" id="btnBulanIni" class="btn btn-success btn-sm">
                                            <i class="fas fa-calendar-week"></i> Laporan Bulan Ini
                                        </button>
                                        <button type="button" id="btnTahunIni" class="btn btn-warning btn-sm">
                                            <i class="fas fa-calendar-check"></i> Laporan Tahun Ini
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- resources/views/laporan/penjualan_index.blade.php --}}
                        {{-- ... (setelah <form id="formFilterLaporan">) ... --}}

                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6 class="mb-1">Ekspor Laporan ke Excel</h6>
                                <small class="text-muted">Pilih periode di atas, lalu klik tombol ekspor yang
                                    sesuai.</small>
                            </div>
                        </div>
                        <form id="formExportExcel" method="GET" action="{{ route('laporan.penjualan.export') }}"
                            target="_blank"> {{-- target="_blank" agar download tidak mengganggu halaman saat ini --}}
                            {{-- Hidden inputs untuk dikirim ke controller export --}}
                            <input type="hidden" name="jenis_laporan_export" id="jenis_laporan_export_hidden">
                            <input type="hidden" name="bulan_export" id="bulan_export_hidden">
                            <input type="hidden" name="tahun_export" id="tahun_export_hidden">
                            <input type="hidden" name="tanggal_export" id="tanggal_export_hidden">

                            <div class="row gy-2">
                                <div class="col-md-3 col-sm-6">
                                    <button type="submit" class="btn btn-success w-100" id="btnExportBulanan">
                                        <i class="fas fa-file-excel"></i> Ekspor Bulanan Pilihan
                                    </button>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <button type="submit" class="btn btn-success w-100" id="btnExportHarian">
                                        <i class="fas fa-file-excel"></i> Ekspor Harian Pilihan
                                    </button>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <button type="submit" class="btn btn-success w-100" id="btnExportTahunIni">
                                        <i class="fas fa-file-excel"></i> Ekspor Tahun Ini
                                    </button>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <button type="submit" class="btn btn-success w-100" id="btnExportBulanIni">
                                        <i class="fas fa-file-excel"></i> Ekspor Bulan Ini
                                    </button>
                                </div>
                            </div>
                            @if (session('error_export'))
                                <div class="alert alert-danger mt-2">{{ session('error_export') }}</div>
                            @endif
                            @if (
                                $errors->has('jenis_laporan_export') ||
                                    $errors->has('bulan_export') ||
                                    $errors->has('tahun_export') ||
                                    $errors->has('tanggal_export'))
                                <div class="alert alert-danger mt-2">
                                    Terjadi kesalahan pada input periode ekspor. Pastikan format benar.
                                </div>
                            @endif
                        </form>
                        {{-- ... --}}

                        {{-- Area untuk Grafik --}}
                        <div class="mb-4" id="areaGrafikContainer" style="display: none;">
                            <div class="card shadow-sm printable-card"> {{-- printable-card untuk styling saat print --}}
                                <div class="card-header" id="judulGrafik">Grafik Penjualan</div>
                                <div class="card-body">
                                    <canvas id="salesChart" style="max-height: 350px;"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Area untuk Grafik Item Terlaris --}}
                        <div class="mt-4" id="areaGrafikItemTerlarisContainer" style="display: none;">
                            <div class="card shadow-sm">
                                <div class="card-header" id="judulGrafikItemTerlaris">Grafik Item Obat Terlaris</div>
                                <div class="card-body">
                                    <canvas id="topItemsChart" style="max-height: 350px;"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Area untuk Tabel Detail --}}
                        <div id="areaLaporan" class="printable-card"> {{-- printable-card untuk styling saat print --}}
                            <div class="text-center text-muted d-print-none"> {{-- d-print-none pada pesan awal --}}
                                <p>Silakan pilih filter atau tombol laporan cepat untuk menampilkan data.</p>
                            </div>
                            {{-- Konten tabel dari AJAX akan dimuat di sini --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    {{-- Menggunakan @push untuk CSS spesifik halaman ini --}}
    <style media="print">
        body * {
            visibility: hidden;
            /* Sembunyikan semua elemen standar */
        }

        #laporanPageContainer,
        #laporanPageContainer * {
            visibility: visible;
            /* Tampilkan hanya container laporan dan isinya */
        }

        #laporanPageContainer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
            /* Beri sedikit padding untuk print */
            font-size: 10pt;
            /* Ukuran font untuk print */
        }

        .d-print-none {
            /* Elemen dengan class ini akan disembunyikan saat print */
            display: none !important;
        }

        .card.printable-card {
            /* Styling untuk card yang ingin dicetak */
            border: 1px solid #dee2e6 !important;
            /* Pastikan border card terlihat */
            box-shadow: none !important;
            margin-bottom: 20px !important;
            /* Spasi antar card jika ada lebih dari satu bagian */
        }

        .card.printable-card .card-header {
            background-color: #f8f9fa !important;
            /* Warna header card yang lebih soft untuk print */
            font-weight: bold;
            padding: 0.5rem 1rem;
        }

        table {
            /* Styling umum untuk tabel di print */
            width: 100% !important;
            border-collapse: collapse !important;
        }

        table th,
        table td {
            border: 1px solid #ccc !important;
            padding: 0.3rem !important;
            font-size: 9pt !important;
        }

        thead.table-dark th {
            /* Ubah background header tabel jadi lebih print-friendly */
            background-color: #e9ecef !important;
            color: #212529 !important;
            -webkit-print-color-adjust: exact !important;
            /* Memaksa print background color di Chrome/Safari */
            print-color-adjust: exact !important;
        }

        #salesChart {
            /* Pastikan chart tidak melebihi lebar halaman */
            max-width: 100% !important;
            height: auto !important;
            /* Biarkan tingginya menyesuaikan atau atur jika perlu */
        }

        @page {
            /* Pengaturan halaman print */
            size: A4 landscape;
            /* Atau portrait jika lebih sesuai */
            margin: 20mm;
        }

        /* Sembunyikan navbar aplikasi */
        nav.navbar {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    {{-- Chart.js sudah di-include di layouts/app.blade.php --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter Bulanan
            const bulanFilterBulananEl = document.getElementById('bulan_filter_bulanan');
            const tahunFilterBulananEl = document.getElementById('tahun_filter_bulanan');
            const btnLihatBulanan = document.getElementById('btnLihatBulanan');

            // Filter Harian
            const tanggalFilterHarianEl = document.getElementById('tanggal_filter_harian');
            const btnLihatHarian = document.getElementById('btnLihatHarian');

            // Tombol Cepat
            const btnBulanIni = document.getElementById('btnBulanIni');
            const btnTahunIni = document.getElementById('btnTahunIni');

            // Tombol Cetak
            const btnCetakLaporanKeseluruhan = document.getElementById('btnCetakLaporanKeseluruhan');

            // Area Tampilan
            const areaLaporan = document.getElementById('areaLaporan');
            const areaGrafikContainer = document.getElementById('areaGrafikContainer');
            const judulGrafikEl = document.getElementById('judulGrafik');
            const judulLaporanUtamaEl = document.getElementById('judulLaporanUtama');
            const salesChartCanvas = document.getElementById('salesChart').getContext('2d');
            let mySalesChart;

            // Grafik Item Terlaris - BARU
            const areaGrafikItemTerlarisContainer = document.getElementById('areaGrafikItemTerlarisContainer');
            const judulGrafikItemTerlarisEl = document.getElementById('judulGrafikItemTerlaris');
            const topItemsChartCanvas = document.getElementById('topItemsChart').getContext('2d');
            let myTopItemsChart; // Variabel untuk instance chart item terlaris

            async function fetchLaporan(jenisLaporan, periodeData = {}) {
                areaLaporan.innerHTML =
                    `<div class="d-flex justify-content-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div></div>`;
                areaGrafikContainer.style.display = 'none';
                areaGrafikItemTerlarisContainer.style.display = 'none'; // Sembunyikan grafik item terlaris
                btnCetakLaporanKeseluruhan.style.display = 'none'; // Sembunyikan tombol cetak saat loading

                if (mySalesChart) {
                    mySalesChart.destroy();
                } // Hancurkan chart penjualan lama sebelum request baru

                if (myTopItemsChart) {
                    myTopItemsChart.destroy();
                } // Hancurkan chart item terlaris lama

                let url = `{{ route('laporan.penjualan.data') }}?jenis_laporan=${jenisLaporan}`;
                let userFriendlyPeriodeJudul = "Laporan Penjualan";

                if (jenisLaporan === 'bulanan_detail_harian') {
                    url += `&bulan=${periodeData.tahun}-${periodeData.bulan}`; // YYYY-MM
                    const dateObjBulan = new Date(periodeData.tahun, periodeData.bulan - 1);
                    userFriendlyPeriodeJudul =
                        `Bulan ${dateObjBulan.toLocaleString('id-ID', { month: 'long', year: 'numeric' })}`;
                } else if (jenisLaporan === 'tahunan_detail_bulanan') {
                    url += `&tahun=${periodeData.tahun}`; // YYYY
                    userFriendlyPeriodeJudul = `Tahun ${periodeData.tahun}`;
                } else if (jenisLaporan === 'harian_spesifik') {
                    url += `&tanggal=${periodeData.tanggal}`; // YYYY-MM-DD
                    const dateObjHari = new Date(periodeData.tanggal +
                        "T00:00:00"); // Pastikan parsing tanggal benar
                    userFriendlyPeriodeJudul =
                        `Tanggal ${dateObjHari.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}`;
                } else {
                    console.error('Jenis laporan tidak valid:', jenisLaporan);
                    areaLaporan.innerHTML =
                        '<p class="text-center text-danger">Jenis laporan tidak dikenal.</p>';
                    return;
                }
                judulLaporanUtamaEl.textContent = `Laporan Penjualan ${userFriendlyPeriodeJudul}`;

                try {
                    const response = await fetch(url);
                    if (!response.ok) {
                        let errorText = `HTTP error! status: ${response.status}`;
                        try {
                            const errorData = await response.json();
                            errorText = errorData.message || errorText;
                        } catch (e) {
                            /* ignore */
                        }
                        throw new Error(errorText);
                    }
                    const data = await response.json();

                    let laporanTampil = false;

                    // Render Grafik Penjualan (yang sudah ada)
                    if (data.chartData && data.chartData.labels && data.chartData.labels.length > 0) {
                        areaGrafikContainer.style.display = 'block';
                        judulGrafikEl.textContent = data.chartData.periodeJudul ||
                            `Grafik Penjualan ${userFriendlyPeriodeJudul}`;
                        renderSalesChart(data.chartData.labels, data.chartData.values, jenisLaporan);
                        laporanTampil = true;
                    } else {
                        areaGrafikContainer.style.display = 'none';
                    }

                    // Render Grafik Item Terlaris (BARU)
                    if (data.topItemsChartData && data.topItemsChartData.labels && data.topItemsChartData.labels
                        .length > 0) {
                        areaGrafikItemTerlarisContainer.style.display = 'block';
                        judulGrafikItemTerlarisEl.textContent = data.topItemsChartData.periodeJudul ||
                            `Item Terlaris ${userFriendlyPeriodeJudul}`;
                        renderTopItemsChart(data.topItemsChartData.labels, data.topItemsChartData.values);
                        laporanTampil = true;
                    } else {
                        areaGrafikItemTerlarisContainer.style.display = 'none';
                    }

                    if (data.tableHtml) {
                        areaLaporan.innerHTML = data.tableHtml;
                        // Periksa apakah tabel benar-benar memiliki konten (selain pesan "Tidak ada data")
                        if (!areaLaporan.querySelector('.alert-info')) { // Jika tidak ada alert info (data ada)
                            laporanTampil = true;
                        }
                    } else {
                        areaLaporan.innerHTML =
                            '<p class="text-center text-info">Tidak ada data untuk ditampilkan.</p>';
                    }

                    if (laporanTampil) {
                        btnCetakLaporanKeseluruhan.style.display =
                            'inline-block'; // Tampilkan tombol cetak jika ada konten
                    } else {
                        btnCetakLaporanKeseluruhan.style.display = 'none';
                        if (!areaGrafikContainer.style.display || areaGrafikContainer.style.display ===
                            'none') {
                            areaLaporan.innerHTML =
                                '<p class="text-center text-info">Tidak ada data penjualan untuk periode yang dipilih.</p>';
                        }
                    }

                } catch (error) {
                    console.error('Error fetching report:', error);
                    areaLaporan.innerHTML =
                        `<div class="alert alert-danger text-center">Gagal memuat laporan: ${error.message}</div>`;
                    areaGrafikContainer.style.display = 'none';
                    areaGrafikItemTerlarisContainer.style.display = 'none';
                    btnCetakLaporanKeseluruhan.style.display = 'none';
                }
            }

            // Event Listeners
            btnLihatBulanan.addEventListener('click', function() {
                const bulan = bulanFilterBulananEl.value;
                const tahun = tahunFilterBulananEl.value;
                if (!bulan || !tahun) {
                    alert('Pilih Bulan dan Tahun untuk Laporan Bulanan.');
                    return;
                }
                fetchLaporan('bulanan_detail_harian', {
                    bulan: bulan,
                    tahun: tahun
                });
            });

            btnLihatHarian.addEventListener('click', function() {
                const tanggal = tanggalFilterHarianEl.value;
                if (!tanggal) {
                    alert('Pilih Tanggal untuk Laporan Harian.');
                    return;
                }
                fetchLaporan('harian_spesifik', {
                    tanggal: tanggal
                });
            });

            btnBulanIni.addEventListener('click', function() {
                const now = new Date();
                const bulanIni = ('0' + (now.getMonth() + 1)).slice(-2);
                const tahunIni = now.getFullYear().toString();
                bulanFilterBulananEl.value = bulanIni; // Update UI filter bulanan
                tahunFilterBulananEl.value = tahunIni;
                fetchLaporan('bulanan_detail_harian', {
                    bulan: bulanIni,
                    tahun: tahunIni
                });
            });

            btnTahunIni.addEventListener('click', function() {
                const tahunIni = new Date().getFullYear().toString();
                tahunFilterBulananEl.value = tahunIni; // Update UI filter tahun bulanan
                fetchLaporan('tahunan_detail_bulanan', {
                    tahun: tahunIni
                });
            });

            // Fungsi untuk merender chart penjualan (sebelumnya renderChart)
            function renderSalesChart(labels, values, chartTypeParam) {
                if (mySalesChart) {
                    mySalesChart.destroy();
                }
                let xAxisTitleText = 'Periode';
                let chartDisplayType = 'line'; // Default

                if (chartTypeParam === 'bulanan_detail_harian') {
                    xAxisTitleText = 'Tanggal';
                } else if (chartTypeParam === 'tahunan_detail_bulanan') {
                    xAxisTitleText = 'Bulan';
                } else if (chartTypeParam === 'harian_spesifik') {
                    // Untuk harian, label X mungkin tidak terlalu relevan jika hanya satu titik
                    // atau kita bisa set tipe chart jadi 'bar'
                    xAxisTitleText = labels[0] || 'Total Penjualan'; // Gunakan label tanggal sebagai judul sumbu X
                    // Jika ingin chart batang tunggal:
                    // chartDisplayType = 'bar';
                }

                mySalesChart = new Chart(salesChartCanvas, {
                    type: chartDisplayType, // Gunakan chartDisplayType
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: values,
                            borderColor: chartTypeParam === 'tahunan_detail_bulanan' ?
                                'rgb(255, 159, 64)' : 'rgb(54, 162, 235)',
                            backgroundColor: chartTypeParam === 'tahunan_detail_bulanan' ?
                                'rgba(255, 159, 64, 0.2)' : 'rgba(54, 162, 235, 0.2)',
                            tension: 0.1,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 7,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        animation: {
                            onComplete: () => {
                                // console.log('Chart animation complete, ready for print if needed.');
                            }
                        },
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
                                title: {
                                    display: true,
                                    text: xAxisTitleText
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return (context.dataset.label || '') + ': Rp ' + new Intl
                                            .NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                }
                            },
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            }

            // Fungsi BARU untuk merender chart item terlaris
            function renderTopItemsChart(labels, values) {
                if (myTopItemsChart) {
                    myTopItemsChart.destroy();
                }

                const backgroundColors = [ // Siapkan beberapa warna jika ingin bar berbeda-beda
                    'rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)', 'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)',
                    'rgba(199, 199, 199, 0.5)', 'rgba(83, 102, 255, 0.5)',
                    'rgba(40, 159, 64, 0.5)', 'rgba(210, 99, 132, 0.5)'
                ];
                const borderColors = backgroundColors.map(color => color.replace('0.5', '1'));

                myTopItemsChart = new Chart(topItemsChartCanvas, {
                    type: 'bar', // Tipe chart batang
                    data: {
                        labels: labels, // Nama-nama obat
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: values, // Jumlah terjual per obat
                            backgroundColor: backgroundColors.slice(0, values
                                .length), // Ambil warna sebanyak data
                            borderColor: borderColors.slice(0, values.length),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        indexAxis: 'y', // Membuat bar menjadi horizontal agar nama obat panjang muat
                        scales: {
                            x: { // Sumbu X (sekarang menjadi jumlah)
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Terjual (Qty)'
                                },
                                ticks: {
                                    precision: 0 // Pastikan angka bulat untuk jumlah
                                }
                            },
                            y: { // Sumbu Y (sekarang menjadi nama obat)
                                title: {
                                    display: true,
                                    text: 'Nama Obat'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false // Biasanya tidak perlu legend jika hanya 1 dataset
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return (context.dataset.label || '') + ': ' + context.parsed.x +
                                            ' unit';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Event listener untuk tombol cetak
            btnCetakLaporanKeseluruhan.addEventListener('click', function() {
                window.print();
            });

            // Sembunyikan tombol cetak pada awalnya
            btnCetakLaporanKeseluruhan.style.display = 'none';

            const jenisLaporanExportHidden = document.getElementById('jenis_laporan_export_hidden');
            const bulanExportHidden = document.getElementById('bulan_export_hidden');
            const tahunExportHidden = document.getElementById('tahun_export_hidden');
            const tanggalExportHidden = document.getElementById('tanggal_export_hidden');

            const btnExportBulanan = document.getElementById('btnExportBulanan');
            const btnExportHarian = document.getElementById('btnExportHarian');
            const btnExportTahunIni = document.getElementById('btnExportTahunIni');
            const btnExportBulanIni = document.getElementById('btnExportBulanIni');

            btnExportBulanan.addEventListener('click', function(e) {
                const bulan = bulanFilterBulananEl.value;
                const tahun = tahunFilterBulananEl.value;
                if (!bulan || !tahun) {
                    alert('Pilih Bulan dan Tahun pada filter Laporan Bulanan terlebih dahulu.');
                    e.preventDefault(); // Mencegah submit form jika validasi gagal
                    return;
                }
                jenisLaporanExportHidden.value = 'bulanan_detail_harian_export';
                bulanExportHidden.value = `${tahun}-${bulan}`;
                tahunExportHidden.value = ''; // Kosongkan yang tidak relevan
                tanggalExportHidden.value = '';
                // Form akan tersubmit otomatis karena type="submit"
            });

            btnExportHarian.addEventListener('click', function(e) {
                const tanggal = tanggalFilterHarianEl.value;
                if (!tanggal) {
                    alert('Pilih Tanggal pada filter Laporan Harian terlebih dahulu.');
                    e.preventDefault();
                    return;
                }
                jenisLaporanExportHidden.value = 'harian_spesifik_export';
                tanggalExportHidden.value = tanggal;
                bulanExportHidden.value = '';
                tahunExportHidden.value = '';
            });

            btnExportTahunIni.addEventListener('click', function(e) {
                const tahunIni = new Date().getFullYear().toString();
                jenisLaporanExportHidden.value = 'tahunan_detail_bulanan_export';
                tahunExportHidden.value = tahunIni;
                bulanExportHidden.value = '';
                tanggalExportHidden.value = '';
            });

            btnExportBulanIni.addEventListener('click', function(e) {
                const now = new Date();
                const bulanIni = ('0' + (now.getMonth() + 1)).slice(-2);
                const tahunIni = now.getFullYear().toString();
                jenisLaporanExportHidden.value = 'bulanan_detail_harian_export';
                bulanExportHidden.value = `${tahunIni}-${bulanIni}`;
                tahunExportHidden.value = '';
                tanggalExportHidden.value = '';
            });

        });
    </script>
@endpush
