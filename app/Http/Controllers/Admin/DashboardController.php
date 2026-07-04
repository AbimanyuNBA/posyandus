<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Balita, Pengukuran, Posyandu, User};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posyandu' => Posyandu::count(),
            'total_kader'    => User::where('role', 'kader')->count(),
            'total_balita'   => Balita::count(),
            'total_stunting' => Pengukuran::whereIn('status_gizi', ['stunting', 'severely_stunting'])
                                    ->whereMonth('tanggal_ukur', now()->month)
                                    ->whereYear('tanggal_ukur', now()->year)
                                    ->count(),
        ];

        // Data grafik — prevalensi stunting 6 bulan terakhir
        $grafikBulanan = collect(range(5, 0))->map(function ($i) {
            $bulan = now()->subMonths($i);
            return [
                'label'   => $bulan->translatedFormat('M Y'),
                'normal'  => Pengukuran::where('status_gizi', 'normal')
                                ->whereMonth('tanggal_ukur', $bulan->month)
                                ->whereYear('tanggal_ukur', $bulan->year)
                                ->count(),
                'stunting' => Pengukuran::whereIn('status_gizi', ['stunting', 'severely_stunting'])
                                ->whereMonth('tanggal_ukur', $bulan->month)
                                ->whereYear('tanggal_ukur', $bulan->year)
                                ->count(),
                'gizi_buruk' => Pengukuran::whereIn('status_gizi', ['gizi_buruk', 'gizi_kurang'])
                                ->whereMonth('tanggal_ukur', $bulan->month)
                                ->whereYear('tanggal_ukur', $bulan->year)
                                ->count(),
            ];
        });

        // Data grafik — distribusi status gizi bulan ini
        $distribusiStatus = Pengukuran::whereMonth('tanggal_ukur', now()->month)
            ->whereYear('tanggal_ukur', now()->year)
            ->selectRaw('status_gizi, COUNT(*) as total')
            ->groupBy('status_gizi')
            ->pluck('total', 'status_gizi');

        // Per posyandu
        $perPosyandu = Posyandu::withCount('balita')
            ->with(['balita.pengukuranTerakhir'])
            ->get()
            ->map(function ($p) {
                $pengukuranBulanIni = Pengukuran::whereHas('balita', fn($q) =>
                    $q->where('posyandu_id', $p->id))
                    ->whereMonth('tanggal_ukur', now()->month)
                    ->whereYear('tanggal_ukur', now()->year);

                return [
                    'nama'        => $p->nama,
                    'kelurahan'   => $p->kelurahan,
                    'total_balita'=> $p->balita_count,
                    'diukur'      => $pengukuranBulanIni->count(),
                    'stunting'    => $pengukuranBulanIni->clone()
                                        ->whereIn('status_gizi', ['stunting','severely_stunting'])
                                        ->count(),
                ];
            });

        return view('admin.dashboard', compact(
            'stats', 'grafikBulanan', 'distribusiStatus', 'perPosyandu'
        ));
    }
}