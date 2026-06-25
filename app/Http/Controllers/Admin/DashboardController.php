<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Balita, Pengukuran, Posyandu, User};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posyandu' => Posyandu::count(),
            'total_kader'    => User::where('role', 'kader')->count(),
            'total_balita'   => Balita::count(),
            'total_stunting' => Pengukuran::whereIn('status_gizi', ['stunting','severely_stunting'])
                                    ->whereMonth('tanggal_ukur', now()->month)
                                    ->count(),
        ];

        $per_posyandu = Posyandu::withCount('balita')
            ->with(['balita' => fn($q) => $q->with('pengukuranTerakhir')])
            ->get();

        return view('admin.dashboard', compact('stats', 'per_posyandu'));
    }
}