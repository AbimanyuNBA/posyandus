<?php
namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Support\PertumbuhanReferensi;
use App\Exports\BalitaDetailExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $posyanduId = auth()->user()->posyandu_id;

        $balita = Balita::where('posyandu_id', $posyanduId)
            ->with('pengukuranTerakhir')
            ->when($request->search, fn($q) =>
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_ortu', 'like', '%' . $request->search . '%')
            )
            ->when($request->status, fn($q) =>
                $q->whereHas('pengukuranTerakhir', fn($q2) =>
                    $q2->where('status_gizi', $request->status)
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('kader.balita.index', compact('balita'));
    }

    public function create()
    {
        return view('kader.balita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:100',
            'nik'           => 'nullable|string|size:16|unique:balita,nik',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ortu'     => 'required|string|max:100',
            'no_hp_ortu'    => 'nullable|string|max:15',
            'alamat'        => 'nullable|string|max:255',
        ]);

        $balita = Balita::create([
            ...$validated,
            'posyandu_id' => auth()->user()->posyandu_id,
        ]);

        return redirect()->route('kader.balita.show', $balita)
                         ->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function show(Balita $balita)
    {
        $this->authorizeBalita($balita);

        $pengukuran = $balita->pengukuran()
            ->with('kader')
            ->paginate(10);

        // Data untuk grafik pertumbuhan (reorder() untuk membersihkan
        // default sorting dari relasi/global scope yang bisa bikin urutan kebalik)
        $grafikData = $balita->pengukuran()
            ->reorder('tanggal_ukur', 'asc')
            ->get(['tanggal_ukur', 'berat_badan', 'tinggi_badan', 'usia_bulan', 'zscore_bbu', 'zscore_tbu']);

        return view('kader.balita.show', compact('balita', 'pengukuran', 'grafikData'));
    }

    public function edit(Balita $balita)
    {
        $this->authorizeBalita($balita);
        return view('kader.balita.edit', compact('balita'));
    }

    public function update(Request $request, Balita $balita)
    {
        $this->authorizeBalita($balita);

        $validated = $request->validate([
            'nama'          => 'required|string|max:100',
            'nik'           => 'nullable|string|size:16|unique:balita,nik,' . $balita->id,
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ortu'     => 'required|string|max:100',
            'no_hp_ortu'    => 'nullable|string|max:15',
            'alamat'        => 'nullable|string|max:255',
        ]);

        $balita->update($validated);

        return redirect()->route('kader.balita.show', $balita)
                         ->with('success', 'Data balita berhasil diperbarui.');
    }

    public function destroy(Balita $balita)
    {
        $this->authorizeBalita($balita);
        $balita->delete();

        return redirect()->route('kader.balita.index')
                         ->with('success', 'Data balita dihapus.');
    }

    public function exportPdf(Balita $balita)
    {
        $this->authorizeBalita($balita);

        [$pengukuran, $terakhir, $chartBBU, $chartTBU] = $this->siapkanDataGrafik($balita);

        $pdf = Pdf::loadView('kader.balita.detail_pdf', [
            'balita'     => $balita,
            'terakhir'   => $terakhir,
            'pengukuran' => $pengukuran,
            'chartBBU'   => $chartBBU,
            'chartTBU'   => $chartTBU,
        ]);

        return $pdf->download('detail-' . Str::slug($balita->nama) . '.pdf');
    }

    public function exportExcel(Balita $balita)
    {
        $this->authorizeBalita($balita);

        [$pengukuran, , $chartBBU, $chartTBU] = $this->siapkanDataGrafik($balita);

        return Excel::download(
            new BalitaDetailExport($balita, $pengukuran, $chartBBU, $chartTBU),
            'detail-' . Str::slug($balita->nama) . '.xlsx'
        );
    }

    private function authorizeBalita(Balita $balita): void
    {
        if ($balita->posyandu_id !== auth()->user()->posyandu_id) {
            abort(403);
        }
    }

    /**
     * Helper bersama: ambil riwayat pengukuran (urut waktu) dan bangun
     * URL grafik statis (via QuickChart) untuk dipakai di export PDF & Excel.
     */
    private function siapkanDataGrafik(Balita $balita): array
    {
        $pengukuran = $balita->pengukuran()->orderBy('tanggal_ukur')->get();
        $terakhir   = $balita->pengukuranTerakhir;

        $labels  = $pengukuran->map(fn ($p) => $p->usia_bulan . ' bln')->toArray();
        $usiaArr = $pengukuran->pluck('usia_bulan')->toArray();
        $bbData  = $pengukuran->pluck('berat_badan')->toArray();
        $tbData  = $pengukuran->pluck('tinggi_badan')->toArray();

        $chartBBU = PertumbuhanReferensi::buildChartUrl(
            $labels,
            'Berat badan (kg)',
            $bbData,
            PertumbuhanReferensi::refBBU2SD($usiaArr),
            PertumbuhanReferensi::refBBU3SD($usiaArr),
            '#1D9E75'
        );

        $chartTBU = PertumbuhanReferensi::buildChartUrl(
            $labels,
            'Tinggi badan (cm)',
            $tbData,
            PertumbuhanReferensi::refTBU2SD($usiaArr),
            PertumbuhanReferensi::refTBU3SD($usiaArr),
            '#378ADD',
            'Batas -2 SD (stunting)',
            'Batas -3 SD (severely stunting)'
        );

        return [$pengukuran, $terakhir, $chartBBU, $chartTBU];
    }

    public function kms(Balita $balita)
{
    $this->authorizeBalita($balita);

    // Ambil semua pengukuran urut dari terlama
    $pengukuran = $balita->pengukuran()
        ->orderBy('tanggal_ukur', 'asc')
        ->get();

    // Siapkan data plot grafik — index berdasarkan usia bulan
    $plotData = $pengukuran->mapWithKeys(fn($p) => [
        $p->usia_bulan => [
            'bb'     => (float) $p->berat_badan,
            'tb'     => (float) $p->tinggi_badan,
            'status' => $p->status_gizi,
            'tanggal'=> $p->tanggal_ukur->format('m/Y'),
            'nt'     => in_array($p->status_gizi, ['normal', 'gizi_lebih', 'obesitas']) ? 'N' : 'T',
        ]
    ]);

    return view('kader.balita.kms', compact('balita', 'pengukuran', 'plotData'));
}

}