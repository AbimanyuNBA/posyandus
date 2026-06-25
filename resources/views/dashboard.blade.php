<x-app-layout>
    <x-slot name="title">Dashboard Kader — {{ $posyandu?->nama }}</x-slot>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-white">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            {{ $posyandu?->nama ?? '-' }} · {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Balita</p>
            <p class="text-3xl font-semibold text-white">{{ $stats['total_balita'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Diukur Bulan Ini</p>
            <p class="text-3xl font-semibold text-teal-400">{{ $stats['diukur_bulan'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Kasus Stunting</p>
            <p class="text-3xl font-semibold text-red-400">{{ $stats['stunting'] }}</p>
        </div>
    </div>

    {{-- Balita terbaru --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800">
            <h2 class="text-sm font-medium text-gray-200">Balita Terbaru</h2>
            <a href="{{ route('kader.balita.index') }}"
               class="text-xs text-teal-400 hover:text-teal-300 transition-colors">
                Lihat semua →
            </a>
        </div>
        <div class="divide-y divide-gray-800">
            @forelse($balita_terbaru as $balita)
            <div class="flex items-center justify-between px-5 py-3">
                <div>
                    <p class="text-sm text-gray-200">{{ $balita->nama }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $balita->usiaBuilanPada() }} bulan ·
                        {{ $balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </p>
                </div>
                @if($balita->pengukuranTerakhir)
                    @php $p = $balita->pengukuranTerakhir @endphp
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ match($p->status_color) {
                            'green'  => 'bg-teal-500/10 text-teal-400',
                            'red'    => 'bg-red-500/10 text-red-400',
                            'yellow' => 'bg-yellow-500/10 text-yellow-400',
                            'orange' => 'bg-orange-500/10 text-orange-400',
                            default  => 'bg-gray-700 text-gray-400',
                        } }}">
                        {{ $p->status_label }}
                    </span>
                @else
                    <span class="text-xs px-2.5 py-1 rounded-full bg-gray-800 text-gray-500">
                        Belum diukur
                    </span>
                @endif
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-500">
                Belum ada data balita. 
                <a href="{{ route('kader.balita.create') }}" 
                   class="text-teal-400 hover:underline">Tambah sekarang</a>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>