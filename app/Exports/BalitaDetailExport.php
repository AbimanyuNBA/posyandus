<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BalitaDetailExport implements WithTitle, WithEvents
{
    public function __construct(
        protected $balita,
        protected Collection $pengukuran,
        protected string $chartBBU,
        protected string $chartTBU
    ) {}

    public function title(): string
    {
        return 'Detail Balita';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $terakhir = $this->balita->pengukuranTerakhir;

                // Ringkasan data anak
                $sheet->setCellValue('A1', 'Nama');
                $sheet->setCellValue('B1', $this->balita->nama);
                $sheet->setCellValue('A2', 'Jenis kelamin');
                $sheet->setCellValue('B2', $this->balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
                $sheet->setCellValue('A3', 'Usia');
                $sheet->setCellValue('B3', $this->balita->usiaBulanPada() . ' bulan');
                $sheet->setCellValue('A4', 'Berat terakhir (kg)');
                $sheet->setCellValue('B4', $terakhir?->berat_badan ?? '—');
                $sheet->setCellValue('A5', 'Tinggi terakhir (cm)');
                $sheet->setCellValue('B5', $terakhir?->tinggi_badan ?? '—');
                $sheet->setCellValue('A6', 'Status gizi terakhir');
                $sheet->setCellValue('B6', $terakhir?->status_label ?? 'Belum diukur');

                $sheet->getStyle('A1:A6')->getFont()->setBold(true);
                $sheet->getColumnDimension('A')->setWidth(22);
                $sheet->getColumnDimension('B')->setWidth(20);

                // Header tabel riwayat
                $headerRow = 8;
                $sheet->fromArray(
                    ['Tanggal', 'Usia', 'BB (kg)', 'TB (cm)', 'Z BB/U', 'Z TB/U', 'Status gizi'],
                    null,
                    "A{$headerRow}"
                );
                $sheet->getStyle("A{$headerRow}:G{$headerRow}")->getFont()->setBold(true);

                $row = $headerRow + 1;
                foreach ($this->pengukuran as $p) {
                    $sheet->fromArray([
                        $p->tanggal_ukur->format('d/m/Y'),
                        $p->usia_bulan . ' bln',
                        $p->berat_badan,
                        $p->tinggi_badan,
                        $p->zscore_bbu ?? '—',
                        $p->zscore_tbu ?? '—',
                        $p->status_label,
                    ], null, "A{$row}");
                    $row++;
                }

                foreach (range('C', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setWidth(14);
                }

                // Sisipkan grafik (diunduh dari QuickChart sebagai gambar statis)
                $imageRow = $row + 2;
                $this->insertChartImage($sheet, $this->chartBBU, "A{$imageRow}");
                $this->insertChartImage($sheet, $this->chartTBU, "F{$imageRow}");
            },
        ];
    }

    private function insertChartImage($sheet, string $url, string $cell): void
    {
        try {
            $contents = @file_get_contents($url);
            if ($contents === false) {
                return;
            }

            $tmpPath = tempnam(sys_get_temp_dir(), 'chart') . '.png';
            file_put_contents($tmpPath, $contents);

            $drawing = new Drawing();
            $drawing->setPath($tmpPath);
            $drawing->setCoordinates($cell);
            $drawing->setWidth(420);
            $drawing->setWorksheet($sheet);
        } catch (\Throwable $e) {
            // Kalau gagal ambil gambar (mis. tidak ada koneksi keluar), Excel tetap
            // dibuat tanpa grafik daripada gagal total.
            report($e);
        }
    }
}