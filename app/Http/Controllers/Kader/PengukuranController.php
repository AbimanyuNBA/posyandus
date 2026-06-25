<?php
namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\{Balita, Pengukuran};
use App\Services\ZScoreService;
use Illuminate\Http\Request;

class PengukuranController extends Controller
{
    public function __construct(private ZScoreService $zscore) {}

    public function create(Balita $balita)
    {
        return view('kader.pengukuran.create', compact('balita'));
    }

    public function store(Request $request, Balita $balita)
    {
        $validated = $request->validate([
            'tanggal_ukur' => 'required|date|before_or_equal:today',
            'berat_badan'  => 'required|numeric|min:0.5|max:50',
            'tinggi_badan' => 'required|numeric|min:30|max:150',
            'catatan'      => 'nullable|string|max:500',
        ]);

        // Hitung usia dalam bulan pada tanggal pengukuran
        $usiaBulan = $balita->usiaBuilanPada($validated['tanggal_ukur']);

        // Kalkulasi Z-score otomatis
        $zscore = $this->zscore->analisis(
            bb: (float) $validated['berat_badan'],
            tb: (float) $validated['tinggi_badan'],
            jk: $balita->jenis_kelamin,
            usiaBulan: $usiaBulan
        );

        $pengukuran = Pengukuran::create([
            'balita_id'    => $balita->id,
            'user_id'      => auth()->id(),
            'tanggal_ukur' => $validated['tanggal_ukur'],
            'berat_badan'  => $validated['berat_badan'],
            'tinggi_badan' => $validated['tinggi_badan'],
            'usia_bulan'   => $usiaBulan,
            'catatan'      => $validated['catatan'],
            ...$zscore,
        ]);

        return redirect()->route('kader.balita.show', $balita)
                         ->with('success', 'Pengukuran berhasil disimpan. Status gizi: ' . $pengukuran->status_label);
    }

    public function destroy(Pengukuran $pengukuran)
    {
        $balita = $pengukuran->balita;
        $pengukuran->delete();

        return redirect()->route('kader.balita.show', $balita)
                         ->with('success', 'Data pengukuran dihapus.');
    }
}