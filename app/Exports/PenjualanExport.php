<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Untuk auto size kolom
use Maatwebsite\Excel\Events\AfterSheet;       // Untuk event styling
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment; // Untuk alignment
use Carbon\Carbon;

class PenjualanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $periodeLaporan;

    public function __construct(Carbon $startDate, Carbon $endDate, string $periodeLaporan)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->periodeLaporan = $periodeLaporan;
    }

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        // Query data penjualan beserta detailnya untuk periode yang dipilih
        return Penjualan::with(['user', 'detailPenjualan.obat'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'asc');
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        // Header utama untuk informasi laporan
        // Baris 1: Judul Laporan
        // Baris 2: Periode Laporan
        // Baris 3: Kosong (sebagai spasi)
        // Baris 4: Header tabel data
        return [
            ['Laporan Penjualan Apotek Berkah Ibu'], // Baris 1
            ['Periode: ' . $this->periodeLaporan],     // Baris 2
            [], // Baris 3 (spasi)
            // Header untuk data tabel
            [
                'No. Transaksi',
                'Tanggal',
                'Waktu',
                'Kasir',
                'Kode Obat',
                'Nama Obat',
                'Satuan',
                'Qty',
                'Harga Satuan (Rp)',
                'Subtotal (Rp)',
                'Total Transaksi (Rp)',
                'Catatan Transaksi'
            ] // Baris 4
        ];
    }

    /**
    * @param mixed $penjualan
    * @return array
    */
    public function map($penjualan): array
    {
        // Karena satu transaksi bisa memiliki banyak item, kita perlu membuat beberapa baris untuk setiap item
        $rows = [];
        $isFirstItem = true;

        foreach ($penjualan->detailPenjualan as $detail) {
            $rows[] = [
                $isFirstItem ? $penjualan->nomor_transaksi : '', // Hanya tampilkan di item pertama
                $isFirstItem ? Carbon::parse($penjualan->created_at)->format('d-m-Y') : '',
                $isFirstItem ? Carbon::parse($penjualan->created_at)->format('H:i:s') : '',
                $isFirstItem ? $penjualan->user->name : '',
                $detail->obat->kode_obat,
                $detail->obat->nama_obat,
                $detail->obat->satuan,
                $detail->jumlah,
                $detail->harga_satuan_saat_transaksi,
                $detail->sub_total,
                $isFirstItem ? $penjualan->total_harga : '',
                $isFirstItem ? $penjualan->catatan : '',
            ];
            $isFirstItem = false;
        }
        // Jika transaksi tidak punya detail (seharusnya tidak terjadi jika data valid),
        // setidaknya tampilkan info transaksi utama.
        if (empty($rows)) {
            $rows[] = [
                $penjualan->nomor_transaksi,
                Carbon::parse($penjualan->created_at)->format('d-m-Y'),
                Carbon::parse($penjualan->created_at)->format('H:i:s'),
                $penjualan->user->name,
                '', // Kode Obat
                '', // Nama Obat
                '', // Satuan
                0,  // Qty
                0,  // Harga Satuan
                0,  // Subtotal
                $penjualan->total_harga,
                $penjualan->catatan,
            ];
        }

        return $rows;
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cell untuk Judul Laporan Utama (A1 sampai L1)
                $lastColumnLetter = $sheet->getHighestDataColumn(); // Misal 'L'
                $sheet->mergeCells('A1:'.$lastColumnLetter.'1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Merge cell untuk Periode Laporan (A2 sampai L2)
                $sheet->mergeCells('A2:'.$lastColumnLetter.'2');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Style untuk Header Tabel Data (Baris ke-4)
                $sheet->getStyle('A4:'.$lastColumnLetter.'4')->getFont()->setBold(true);
                $sheet->getStyle('A4:'.$lastColumnLetter.'4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:'.$lastColumnLetter.'4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Format angka untuk kolom harga, qty, subtotal, total
                // Misal, jika data dimulai dari baris ke-5
                $firstDataRow = 5;
                $lastDataRow = $sheet->getHighestDataRow();

                if ($lastDataRow >= $firstDataRow) {
                    // Kolom Qty (H)
                    $sheet->getStyle('H'.$firstDataRow.':H'.$lastDataRow)->getNumberFormat()->setFormatCode('#,##0');
                    // Kolom Harga Satuan (I), Subtotal (J), Total Transaksi (K)
                    $sheet->getStyle('I'.$firstDataRow.':K'.$lastDataRow)->getNumberFormat()->setFormatCode('#,##0.00'); // Atau '_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)' untuk format Rupiah
                }
            },
        ];
    }
}