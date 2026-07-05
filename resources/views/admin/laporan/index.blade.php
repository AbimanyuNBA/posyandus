<x-app-layout>
<x-slot name="title">Laporan Rekap</x-slot>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">Laporan Rekap</h1>
        <p class="text-sm text-gray-500 mt-0.5">Data pengukuran dan status gizi balita</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100
                  text-red-600 text-sm rounded-lg transition-colors border border-red-200 font-medium">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export PDF
        </a>
        <a href="{{ route('admin.laporan.excel', request()->query()) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 hover:bg-green-100
                  text-green-600 text-sm rounded-lg transition-colors border border-green-200 font-medium">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export Excel
        </a>
    </div>
</div>

{{-- Filter --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <select name="bulan"
            class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm
                   text-gray-700 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors">
        @foreach(range(1,12) as $b)
            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
            </option>
        @endforeach
    </select>

    <select name="tahun"
            class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm
                   text-gray-700 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors">
        @foreach(range(now()->year, now()->year - 3, -1) as $t)
            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
    </select>

    <select name="posyandu_id"
            class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm
                   text-gray-700 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors">
        <option value="">Semua posyandu</option>
        @foreach($posyandu as $p)
            <option value="{{ $p->id }}" {{ $posyanduId == $p->id ? 'selected' : '' }}>
                {{ $p->nama }}
            </option>
        @endforeach
    </select>

    <button type="submit"
            class="px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white text-sm
                   font-medium rounded-lg transition-colors shadow-sm">
        Terapkan
    </button>
</form>

{{-- Rekap cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Total Pengukuran', 'value'=>$rekap['total'],       'color'=>'text-gray-800'],
        ['label'=>'Normal',           'value'=>$rekap['normal'],      'color'=>'text-teal-600'],
        ['label'=>'Stunting',         'value'=>$rekap['stunting'],    'color'=>'text-orange-600'],
        ['label'=>'Gizi Buruk/Kurang','value'=>$rekap['gizi_buruk'], 'color'=>'text-red-600'],
    ] as $c)
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1 font-medium">{{ $c['label'] }}</p>
        <p class="text-2xl font-bold {{ $c['color'] }}">{{ $c['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Tabel --}}
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200 bg-gray-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Balita</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Posyandu</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">BB (kg)</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">TB (cm)</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Z BB/U</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Z TB/U</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($pengukuran as $p)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5">
                    <p class="text-gray-800 font-medium">{{ $p->balita->nama }}</p>
                    <p class="text-xs text-gray-400">{{ $p->balita->usiaBulanPada($p->tanggal_ukur) }} bulan</p>
                </td>
                <td class="px-5 py-3.5 text-gray-600 hidden md:table-cell">
                    {{ $p->balita->posyandu->nama }}
                </td>
                <td class="px-5 py-3.5 text-center text-gray-700 font-medium">{{ $p->berat_badan }}</td>
                <td class="px-5 py-3.5 text-center text-gray-700 font-medium">{{ $p->tinggi_badan }}</td>
                <td class="px-5 py-3.5 text-center text-gray-500 hidden lg:table-cell">
                    {{ $p->zscore_bbu ?? '—' }}
                </td>
                <td class="px-5 py-3.5 text-center text-gray-500 hidden lg:table-cell">
                    {{ $p->zscore_tbu ?? '—' }}
                </td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border
                        {{ match($p->status_color) {
                            'green'  => 'bg-teal-50 text-teal-700 border-teal-100',
                            'red'    => 'bg-red-50 text-red-700 border-red-100',
                            'yellow' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'orange' => 'bg-orange-50 text-orange-700 border-orange-100',
                            default  => 'bg-gray-100 text-gray-600 border-gray-200',
                        } }}">
                        {{ $p->status_label }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-gray-500 text-xs hidden md:table-cell">
                    {{ $p->tanggal_ukur->format('d M Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">
                    Tidak ada data untuk filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($pengukuran->hasPages())
    <div class="px-5 py-3 border-t border-gray-200 bg-gray-50">
        {{ $pengukuran->links() }}
    </div>
    @endif
</div>
</x-app-layout>