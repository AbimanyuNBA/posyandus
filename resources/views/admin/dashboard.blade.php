<x-app-layout>
<x-slot name="title">Dashboard Admin</x-slot>

<div class="mb-6">
    <h1 class="text-xl font-semibold text-white">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-0.5">
        Monitoring stunting — {{ now()->translatedFormat('F Y') }}
    </p>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label' => 'Total Posyandu', 'value' => $stats['total_posyandu'], 'color' => 'teal'],
        ['label' => 'Total Kader',    'value' => $stats['total_kader'],    'color' => 'blue'],
        ['label' => 'Total Balita',   'value' => $stats['total_balita'],   'color' => 'purple'],
        ['label' => 'Stunting Bulan Ini', 'value' => $stats['total_stunting'], 'color' => 'red'],
    ] as $card)
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">{{ $card['label'] }}</p>
        <p class="text-3xl font-semibold
            {{ match($card['color']) {
                'teal'   => 'text-teal-400',
                'blue'   => 'text-blue-400',
                'purple' => 'text-purple-400',
                'red'    => 'text-red-400',
                default  => 'text-white',
            } }}">
            {{ $card['value'] }}
        </p>
    </div>
    @endforeach
</div>

{{-- Grafik --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">

    {{-- Tren 6 bulan --}}
    <div class="lg:col-span-2 bg-gray-900 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-medium text-gray-200 mb-4">
            Tren status gizi — 6 bulan terakhir
        </h2>
        <div class="relative h-56">
            <canvas id="chartTren"></canvas>
        </div>
    </div>

    {{-- Distribusi --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <h2 class="text-sm font-medium text-gray-200 mb-4">
            Distribusi bulan ini
        </h2>
        <div class="relative h-56 flex items-center justify-center">
            <canvas id="chartDistribusi"></canvas>
        </div>
    </div>
</div>

{{-- Tabel per posyandu --}}
<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
        <h2 class="text-sm font-medium text-gray-200">Rekap per posyandu</h2>
        <a href="{{ route('admin.laporan.index') }}"
           class="text-xs text-teal-400 hover:text-teal-300 transition-colors">
            Lihat laporan lengkap →
        </a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-800">
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Posyandu</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kelurahan</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Balita</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Diukur</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Stunting</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @foreach($perPosyandu as $p)
            <tr class="hover:bg-gray-800/50 transition-colors">
                <td class="px-5 py-3.5 text-gray-200 font-medium">{{ $p['nama'] }}</td>
                <td class="px-5 py-3.5 text-gray-400">{{ $p['kelurahan'] }}</td>
                <td class="px-5 py-3.5 text-center text-gray-300">{{ $p['total_balita'] }}</td>
                <td class="px-5 py-3.5 text-center text-teal-400">{{ $p['diukur'] }}</td>
                <td class="px-5 py-3.5 text-center">
                    @if($p['stunting'] > 0)
                        <span class="px-2 py-0.5 rounded-full text-xs bg-red-500/10 text-red-400">
                            {{ $p['stunting'] }}
                        </span>
                    @else
                        <span class="text-gray-600">0</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const grafikData = @json($grafikBulanan);
const distribusi = @json($distribusiStatus);

// ── Tren 6 bulan ──
new Chart(document.getElementById('chartTren'), {
    type: 'bar',
    data: {
        labels: grafikData.map(d => d.label),
        datasets: [
            {
                label: 'Normal',
                data: grafikData.map(d => d.normal),
                backgroundColor: '#1D9E7520',
                borderColor: '#1D9E75',
                borderWidth: 1.5,
                borderRadius: 4,
            },
            {
                label: 'Stunting',
                data: grafikData.map(d => d.stunting),
                backgroundColor: '#E2534A20',
                borderColor: '#E2534A',
                borderWidth: 1.5,
                borderRadius: 4,
            },
            {
                label: 'Gizi Buruk/Kurang',
                data: grafikData.map(d => d.gizi_buruk),
                backgroundColor: '#EF9F2720',
                borderColor: '#EF9F27',
                borderWidth: 1.5,
                borderRadius: 4,
            },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#9ca3af', font: { size: 11 } } } },
        scales: {
            x: { ticks: { color: '#6b7280', font: { size: 11 } }, grid: { color: '#1f2937' } },
            y: { ticks: { color: '#6b7280', font: { size: 11 } }, grid: { color: '#1f2937' }, beginAtZero: true },
        }
    }
});

// ── Distribusi donut ──
const statusLabels = {
    normal: 'Normal', stunting: 'Stunting', severely_stunting: 'Severely Stunting',
    gizi_buruk: 'Gizi Buruk', gizi_kurang: 'Gizi Kurang',
    gizi_lebih: 'Gizi Lebih', obesitas: 'Obesitas',
};
const colors = {
    normal: '#1D9E75', stunting: '#EF9F27', severely_stunting: '#E2534A',
    gizi_buruk: '#993C1D', gizi_kurang: '#FAC775', gizi_lebih: '#378ADD', obesitas: '#534AB7',
};
const keys   = Object.keys(distribusi);
new Chart(document.getElementById('chartDistribusi'), {
    type: 'doughnut',
    data: {
        labels: keys.map(k => statusLabels[k] ?? k),
        datasets: [{
            data: keys.map(k => distribusi[k]),
            backgroundColor: keys.map(k => colors[k] ?? '#6b7280'),
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom', labels: { color: '#9ca3af', font: { size: 11 }, padding: 12 } }
        }
    }
});
</script>
@endpush
</x-app-layout>