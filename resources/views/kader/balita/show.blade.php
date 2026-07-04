<x-app-layout>
<x-slot name="title">Detail — {{ $balita->nama }}</x-slot>

<div class="mb-6">
    <a href="{{ route('kader.balita.index') }}"
       class="text-xs text-gray-500 hover:text-gray-300 transition-colors">
        ← Kembali ke daftar balita
    </a>
    <div class="flex items-start justify-between mt-3">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-base font-semibold shrink-0
                {{ $balita->jenis_kelamin === 'L' ? 'bg-blue-500/10 text-blue-400' : 'bg-pink-500/10 text-pink-400' }}">
                {{ strtoupper(substr($balita->nama, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-semibold text-white">{{ $balita->nama }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    · {{ $balita->usiaBulanPada() }} bulan
                    · {{ $balita->tanggal_lahir->format('d M Y') }}
                </p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('kader.balita.edit', $balita) }}"
               class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300
                      text-sm rounded-lg transition-colors">
                Edit data
            </a>
            <a href="{{ route('kader.balita.pengukuran.create', $balita) }}"
               class="px-4 py-2 bg-teal-500 hover:bg-teal-400 text-gray-950
                      text-sm font-medium rounded-lg transition-colors">
                + Input pengukuran
            </a>
        </div>
    </div>
</div>

{{-- Info cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @php $terakhir = $balita->pengukuranTerakhir @endphp
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Berat terakhir</p>
        <p class="text-2xl font-semibold text-white">
            {{ $terakhir?->berat_badan ?? '—' }}
            <span class="text-sm font-normal text-gray-500">kg</span>
        </p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Tinggi terakhir</p>
        <p class="text-2xl font-semibold text-white">
            {{ $terakhir?->tinggi_badan ?? '—' }}
            <span class="text-sm font-normal text-gray-500">cm</span>
        </p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Z-score TB/U</p>
        <p class="text-2xl font-semibold
            {{ ($terakhir?->zscore_tbu ?? 0) < -2 ? 'text-red-400' : 'text-white' }}">
            {{ $terakhir?->zscore_tbu ?? '—' }}
        </p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Status gizi</p>
        @if($terakhir)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                {{ match($terakhir->status_color) {
                    'green'  => 'bg-teal-500/10 text-teal-400',
                    'red'    => 'bg-red-500/10 text-red-400',
                    'yellow' => 'bg-yellow-500/10 text-yellow-400',
                    'orange' => 'bg-orange-500/10 text-orange-400',
                    default  => 'bg-gray-700 text-gray-400',
                } }}">
                {{ $terakhir->status_label }}
            </span>
        @else
            <p class="text-gray-600 text-sm mt-1">Belum diukur</p>
        @endif
    </div>
</div>

{{-- Alert stunting --}}
@if($terakhir && in_array($terakhir->status_gizi, ['stunting','severely_stunting','gizi_buruk']))
<div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3">
    <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <div>
        <p class="text-sm font-medium text-red-400">
            Perhatian — {{ $terakhir->status_label }}
        </p>
        <p class="text-xs text-red-400/70 mt-0.5">
            Balita ini terdeteksi {{ $terakhir->status_label }} pada pengukuran terakhir
            ({{ $terakhir->tanggal_ukur->format('d M Y') }}).
            Segera laporkan ke petugas puskesmas.
        </p>
    </div>
</div>
@endif

{{-- Grafik pertumbuhan --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-medium text-gray-200 mb-1">Grafik berat badan / usia (BB/U)</h2>
        <p class="text-xs text-gray-500 mb-4">Garis putus = batas -2 SD (referensi WHO)</p>
        <div class="relative h-52">
            <canvas id="chartBBU"></canvas>
        </div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-medium text-gray-200 mb-1">Grafik tinggi badan / usia (TB/U)</h2>
        <p class="text-xs text-gray-500 mb-4">Garis putus = batas -2 SD stunting (referensi WHO)</p>
        <div class="relative h-52">
            <canvas id="chartTBU"></canvas>
        </div>
    </div>
</div>

{{-- Riwayat pengukuran --}}
<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800">
        <h2 class="text-sm font-medium text-gray-200">Riwayat pengukuran</h2>
        <span class="text-xs text-gray-500">{{ $pengukuran->total() }} sesi</span>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-800">
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Usia</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">BB (kg)</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">TB (cm)</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Z BB/U</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Z TB/U</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($pengukuran as $p)
            <tr class="hover:bg-gray-800/40 transition-colors">
                <td class="px-5 py-3.5 text-gray-300">
                    {{ $p->tanggal_ukur->format('d M Y') }}
                </td>
                <td class="px-5 py-3.5 text-center text-gray-400">
                    {{ $p->usia_bulan }} bln
                </td>
                <td class="px-5 py-3.5 text-center text-gray-300">{{ $p->berat_badan }}</td>
                <td class="px-5 py-3.5 text-center text-gray-300">{{ $p->tinggi_badan }}</td>
                <td class="px-5 py-3.5 text-center hidden lg:table-cell
                    {{ ($p->zscore_bbu ?? 0) < -2 ? 'text-red-400' : 'text-gray-400' }}">
                    {{ $p->zscore_bbu ?? '—' }}
                </td>
                <td class="px-5 py-3.5 text-center hidden lg:table-cell
                    {{ ($p->zscore_tbu ?? 0) < -2 ? 'text-red-400' : 'text-gray-400' }}">
                    {{ $p->zscore_tbu ?? '—' }}
                </td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                        {{ match($p->status_color) {
                            'green'  => 'bg-teal-500/10 text-teal-400',
                            'red'    => 'bg-red-500/10 text-red-400',
                            'yellow' => 'bg-yellow-500/10 text-yellow-400',
                            'orange' => 'bg-orange-500/10 text-orange-400',
                            default  => 'bg-gray-700 text-gray-400',
                        } }}">
                        {{ $p->status_label }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-right">
                <form action="{{ route('kader.pengukuran.destroy', $p->id) }}" 
                    method="POST" 
                    onsubmit="return confirm('Hapus data pengukuran ini?')">
                    @csrf 
                    @method('DELETE')
                    <button type="submit"
                            class="text-xs text-gray-600 hover:text-red-400 transition-colors">
                        Hapus
                    </button>
                </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-10 text-center text-gray-500 text-sm">
                    Belum ada data pengukuran.
                    <a href="{{ route('kader.pengukuran.create', $balita) }}"
                       class="text-teal-400 hover:underline">Input sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($pengukuran->hasPages())
    <div class="px-5 py-3 border-t border-gray-800">
        {{ $pengukuran->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const riwayat = @json($grafikData);

const labels  = riwayat.map(d => d.usia_bulan + ' bln');
const bbData  = riwayat.map(d => d.berat_badan);
const tbData  = riwayat.map(d => d.tinggi_badan);
const usiaArr = riwayat.map(d => d.usia_bulan);

// Garis referensi -2 SD WHO BB/U laki-laki (simplified)
// Untuk produksi: load dari endpoint API berdasarkan jenis kelamin
const refBBU = usiaArr.map(u => {
    const tbl = {0:2.1,3:4.5,6:6.0,9:7.2,12:8.1,18:9.4,24:10.5,36:12.2,48:13.7,60:15.3};
    const keys = Object.keys(tbl).map(Number).sort((a,b)=>a-b);
    const nearest = keys.reduce((prev,curr) =>
        Math.abs(curr-u) < Math.abs(prev-u) ? curr : prev);
    return tbl[nearest];
});

const refTBU = usiaArr.map(u => {
    const tbl = {0:46.3,3:57.3,6:63.6,9:68.0,12:71.5,18:76.9,24:81.7,36:88.7,48:94.9,60:100.7};
    const keys = Object.keys(tbl).map(Number).sort((a,b)=>a-b);
    const nearest = keys.reduce((prev,curr) =>
        Math.abs(curr-u) < Math.abs(prev-u) ? curr : prev);
    return tbl[nearest];
});

const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { labels: { color: '#9ca3af', font: { size: 11 }, boxWidth: 24 } }
    },
    scales: {
        x: { ticks: { color: '#6b7280', font:{size:11} }, grid: { color: '#1f2937' } },
        y: { ticks: { color: '#6b7280', font:{size:11} }, grid: { color: '#1f2937' }, beginAtZero: false },
    }
};

// BB/U chart
new Chart(document.getElementById('chartBBU'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Berat badan (kg)',
                data: bbData,
                borderColor: '#1D9E75',
                backgroundColor: '#1D9E7520',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#1D9E75',
                tension: 0.3,
                fill: false,
            },
            {
                label: 'Batas -2 SD WHO',
                data: refBBU,
                borderColor: '#E2534A',
                borderWidth: 1.5,
                borderDash: [6, 4],
                pointRadius: 0,
                fill: false,
            }
        ]
    },
    options: chartDefaults,
});

// TB/U chart
new Chart(document.getElementById('chartTBU'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Tinggi badan (cm)',
                data: tbData,
                borderColor: '#378ADD',
                backgroundColor: '#378ADD20',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#378ADD',
                tension: 0.3,
                fill: false,
            },
            {
                label: 'Batas -2 SD WHO (stunting)',
                data: refTBU,
                borderColor: '#E2534A',
                borderWidth: 1.5,
                borderDash: [6, 4],
                pointRadius: 0,
                fill: false,
            }
        ]
    },
    options: chartDefaults,
});
</script>
@endpush
</x-app-layout>