
@if($penjualan->isEmpty())
    <div class="alert alert-info text-center">
        Tidak ada data penjualan untuk periode yang dipilih.
    </div>
@else
    <div class="alert alert-secondary">
        <strong>Periode Laporan: {{ $periode }}</strong><br>
        Total Transaksi: {{ $penjualan->count() }} <br>
        Total Pendapatan: <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Total Harga (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $trx->nomor_transaksi }}</td>
                        <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                        <td>{{ $trx->user->name }}</td>
                        <td class="text-end">{{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('transaksi.struk', $trx->id) }}" target="_blank" class="btn btn-sm btn-info" title="Lihat Struk">
                                <i class="fas fa-receipt"></i> Struk
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">GRAND TOTAL PENDAPATAN</td>
                    <td colspan="2" class="text-end fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <script>
        function printLaporanArea() {
            // Cara sederhana untuk print hanya bagian laporan,
            // mungkin perlu penyesuaian CSS lebih lanjut jika kompleks.
            const laporanKonten = document.getElementById('areaLaporan').innerHTML;
            const printWindow = window.open('', '_blank', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Cetak Laporan</title>');
            // Sertakan stylesheet Bootstrap atau custom style jika diperlukan
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('<style> body { padding: 20px; } .d-print-none { display:none!important; } @page { size: A4 landscape; margin: 20mm; } table { font-size: 10pt; } </style>'); // Custom print style
            printWindow.document.write('</head><body>');
            printWindow.document.write(laporanKonten);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            setTimeout(() => { // Beri waktu untuk load CSS
                printWindow.print();
                // printWindow.close(); // jangan langsung close agar user bisa save as PDF
            }, 500);
        }
    </script>

@endif