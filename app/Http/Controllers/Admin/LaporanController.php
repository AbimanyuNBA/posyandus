<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pengukuran, Posyandu};
use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan      = $request->bulan ?? now()->month;
        $tahun      = $request->tahun ?? now()->year;
        $posyanduId = $request->posyandu_id;

        $query = Pengukuran::with(['balita.posyandu', 'kader'])
            ->whereMonth('tanggal_ukur', $bulan)
            ->whereYear('tanggal_ukur', $tahun);

        if ($posyanduId) {
            $query->whereHas('balita', fn($q) =>
                $q->where('posyandu_id', $posyanduId));
        }

        $rekap = [
            'total'      => (clone $query)->count(),
            'normal'     => (clone $query)->where('status_gizi', 'normal')->count(),
            'stunting'   => (clone $query)->whereIn('status_gizi', ['stunting','severely_stunting'])->count(),
            'gizi_buruk' => (clone $query)->whereIn('status_gizi', ['gizi_buruk','gizi_kurang'])->count(),
        ];

        $pengukuran = $query->latest('tanggal_ukur')->paginate(20)->withQueryString();
        $posyandu   = Posyandu::orderBy('nama')->get();

        return view('admin.laporan.index', compact(
            'pengukuran', 'rekap', 'posyandu', 'bulan', 'tahun', 'posyanduId'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulan      = $request->bulan ?? now()->month;
        $tahun      = $request->tahun ?? now()->year;
        $posyanduId = $request->posyandu_id;

        $query = Pengukuran::with(['balita.posyandu', 'kader'])
            ->whereMonth('tanggal_ukur', $bulan)
            ->whereYear('tanggal_ukur', $tahun);

        if ($posyanduId) {
            $query->whereHas('balita', fn($q) =>
                $q->where('posyandu_id', $posyanduId));
        }

        $pengukuran = $query->latest('tanggal_ukur')->get();

        $rekap = [
            'total'      => $pengukuran->count(),
            'normal'     => $pengukuran->where('status_gizi', 'normal')->count(),
            'stunting'   => $pengukuran->whereIn('status_gizi', ['stunting','severely_stunting'])->count(),
            'gizi_buruk' => $pengukuran->whereIn('status_gizi', ['gizi_buruk','gizi_kurang'])->count(),
        ];

        $namaBulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');
        $posyandu  = $posyanduId ? Posyandu::find($posyanduId)?->nama : 'Semua Posyandu';

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'pengukuran', 'rekap', 'bulan', 'tahun', 'namaBulan', 'posyandu'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-stunting-{$namaBulan}-{$tahun}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $bulan      = $request->bulan ?? now()->month;
        $tahun      = $request->tahun ?? now()->year;
        $posyanduId = $request->posyandu_id;

        $namaBulan = \Carbon\Carbon::create()->month($bulan)->format('F');

        return Excel::download(
            new LaporanExport($bulan, $tahun, $posyanduId),
            "laporan-stunting-{$namaBulan}-{$tahun}.xlsx"
        );
    }
}