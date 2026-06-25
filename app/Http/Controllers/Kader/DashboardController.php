<?php
namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Pengukuran;

class DashboardController extends Controller
{
    public function index()
    {
        $posyandu = auth()->user()->posyandu;

        $stats = [
            'total_balita'  => Balita::where('posyandu_id', $posyandu?->id)->count(),
            'diukur_bulan'  => Pengukuran::whereHas('balita', fn($q) =>
                                    $q->where('posyandu_id', $posyandu?->id))
                                ->whereMonth('tanggal_ukur', now()->month)
                                ->whereYear('tanggal_ukur', now()->year)
                                ->count(),
            'stunting'      => Pengukuran::whereHas('balita', fn($q) =>
                                    $q->where('posyandu_id', $posyandu?->id))
                                ->whereIn('status_gizi', ['stunting','severely_stunting'])
                                ->whereMonth('tanggal_ukur', now()->month)
                                ->count(),
        ];

        $balita_terbaru = Balita::where('posyandu_id', $posyandu?->id)
            ->with('pengukuranTerakhir')
            ->latest()
            ->take(5)
            ->get();

        return view('kader.dashboard', compact('stats', 'balita_terbaru', 'posyandu'));
    }
}