<?php
namespace App\Exports;

use App\Models\Pengukuran;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    public function __construct(
        private int $bulan,
        private int $tahun,
        private ?int $posyanduId = null
    ) {}

    public function query()
    {
        return Pengukuran::with(['balita.posyandu', 'kader'])
            ->whereMonth('tanggal_ukur', $this->bulan)
            ->whereYear('tanggal_ukur', $this->tahun)
            ->when($this->posyanduId, fn($q) =>
                $q->whereHas('balita', fn($q2) =>
                    $q2->where('posyandu_id', $this->posyanduId)
                )
            )
            ->latest('tanggal_ukur');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Balita',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Nama Orang Tua',
            'Posyandu',
            'Tanggal Ukur',
            'Usia (Bulan)',
            'Berat Badan (kg)',
            'Tinggi Badan (cm)',
            'Z-Score BB/U',
            'Z-Score TB/U',
            'Z-Score BB/TB',
            'Status Gizi',
            'Dicatat Oleh',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->balita->nama,
            $row->balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $row->balita->tanggal_lahir->format('d/m/Y'),
            $row->balita->nama_ortu,
            $row->balita->posyandu->nama ?? '-',
            $row->tanggal_ukur->format('d/m/Y'),
            $row->usia_bulan,
            $row->berat_badan,
            $row->tinggi_badan,
            $row->zscore_bbu ?? '-',
            $row->zscore_tbu ?? '-',
            $row->zscore_bbtb ?? '-',
            $row->status_label,
            $row->kader->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row — bold + background
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F6E56'], // teal-700
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan ' . \Carbon\Carbon::create()->month($this->bulan)->format('F') . ' ' . $this->tahun;
    }
}