<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Posyandu;
use Illuminate\Http\Request;

class PosyanduController extends Controller
{
    public function index()
    {
        $posyandu = Posyandu::withCount('balita')
            ->withCount(['balita as kader_count' => fn($q) =>
                $q->getModel()->newQuery()
                  ->from('users')
                  ->whereColumn('posyandu_id', 'posyandu.id')
            ])
            ->latest()
            ->paginate(15);

        // Lebih simpel — hitung kader terpisah
        $posyandu = Posyandu::withCount('balita')->latest()->paginate(15);

        return view('admin.posyandu.index', compact('posyandu'));
    }

    public function create()
    {
        return view('admin.posyandu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:100',
            'alamat'     => 'nullable|string|max:255',
            'kelurahan'  => 'required|string|max:100',
            'kecamatan'  => 'required|string|max:100',
            'kota'       => 'required|string|max:100',
            'kontak'     => 'nullable|string|max:20',
        ]);

        Posyandu::create($validated);

        return redirect()->route('admin.posyandu.index')
                         ->with('success', 'Posyandu berhasil ditambahkan.');
    }

    public function edit(Posyandu $posyandu)
    {
        return view('admin.posyandu.edit', compact('posyandu'));
    }

    public function update(Request $request, Posyandu $posyandu)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:100',
            'alamat'    => 'nullable|string|max:255',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kota'      => 'required|string|max:100',
            'kontak'    => 'nullable|string|max:20',
        ]);

        $posyandu->update($validated);

        return redirect()->route('admin.posyandu.index')
                         ->with('success', 'Data posyandu berhasil diperbarui.');
    }

    public function destroy(Posyandu $posyandu)
    {
        $posyandu->delete();
        return redirect()->route('admin.posyandu.index')
                         ->with('success', 'Posyandu dihapus.');
    }
}