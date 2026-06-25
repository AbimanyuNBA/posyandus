<?php
namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
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

        // Data untuk grafik pertumbuhan
        $grafikData = $balita->pengukuran()
            ->orderBy('tanggal_ukur')
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

    private function authorizeBalita(Balita $balita): void
    {
        if ($balita->posyandu_id !== auth()->user()->posyandu_id) {
            abort(403);
        }
    }
}