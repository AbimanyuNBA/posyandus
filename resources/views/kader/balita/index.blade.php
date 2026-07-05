<x-app-layout>
<x-slot name="title">Data Balita</x-slot>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-semibold text-gray-900">Data Balita</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ auth()->user()->posyandu?->nama }}</p>
    </div>
    <a href="{{ route('kader.balita.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 hover:bg-teal-400
              text-white text-sm font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Balita
    </a>
</div>

{{-- Filter & search --}}
<form method="GET" class="flex gap-3 mb-5">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama balita atau orang tua..."
           class="flex-1 bg-white border border-gray-300 rounded-lg px-4 py-2
                  text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                  focus:border-teal-500 transition-colors">
    <select name="status"
            class="bg-white border border-gray-300 rounded-lg px-3 py-2
                   text-sm text-gray-800 focus:outline-none focus:border-teal-500">
        <option value="">Semua status</option>
        <option value="normal"            {{ request('status') === 'normal' ? 'selected' : '' }}>Normal</option>
        <option value="stunting"          {{ request('status') === 'stunting' ? 'selected' : '' }}>Stunting</option>
        <option value="severely_stunting" {{ request('status') === 'severely_stunting' ? 'selected' : '' }}>Severely Stunting</option>
        <option value="gizi_buruk"        {{ request('status') === 'gizi_buruk' ? 'selected' : '' }}>Gizi Buruk</option>
        <option value="gizi_kurang"       {{ request('status') === 'gizi_kurang' ? 'selected' : '' }}>Gizi Kurang</option>
    </select>
    <button type="submit"
            class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm
                   rounded-lg transition-colors">Cari</button>
</form>

{{-- Tabel --}}
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Usia</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Orang Tua</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status Gizi</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Pengukuran Terakhir</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($balita as $b)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold shrink-0
                            {{ $b->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                            {{ strtoupper(substr($b->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $b->nama }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $b->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-gray-600 hidden md:table-cell">
                    {{ $b->usiaBulanPada() }} bulan
                </td>
                <td class="px-5 py-3.5 text-gray-600 hidden lg:table-cell">
                    {{ $b->nama_ortu }}
                </td>
                <td class="px-5 py-3.5">
                    @if($b->pengukuranTerakhir)
                        @php $p = $b->pengukuranTerakhir @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            {{ match($p->status_color) {
                                'green'  => 'bg-teal-50 text-teal-700',
                                'red'    => 'bg-red-50 text-red-600',
                                'yellow' => 'bg-yellow-50 text-yellow-700',
                                'orange' => 'bg-orange-50 text-orange-600',
                                default  => 'bg-gray-100 text-gray-500'
                            } }}">
                            {{ $p->status_label }}
                        </span>
                    @else
                        <span class="text-xs text-gray-400">Belum diukur</span>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-gray-500 text-xs hidden lg:table-cell">
                    {{ $b->pengukuranTerakhir?->tanggal_ukur?->format('d M Y') ?? '—' }}
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('kader.balita.pengukuran.create', $b) }}"
                        class="text-xs px-3 py-1.5 bg-teal-50 text-teal-700 hover:bg-teal-100
                                rounded-lg transition-colors whitespace-nowrap">
                            + Ukur
                        </a>
                        <a href="{{ route('kader.balita.show', $b) }}"
                           class="text-xs px-3 py-1.5 bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-900
                                  rounded-lg transition-colors">
                            Detail
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-500 text-sm">
                    Belum ada data balita.
                    <a href="{{ route('kader.balita.create') }}" class="text-teal-600 hover:underline">
                        Tambah sekarang
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($balita->hasPages())
    <div class="px-5 py-3 border-t border-gray-200">
        {{ $balita->links() }}
    </div>
    @endif
</div>
</x-app-layout>