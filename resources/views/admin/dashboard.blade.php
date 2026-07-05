<x-app-layout>
<x-slot name="title">Dashboard Admin</x-slot>

<div class="mb-6">
    <h1 class="text-xl font-semibold text-[#0F766E]">Dashboard</h1>
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
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">{{ $card['label'] }}</p>
        <p class="text-3xl font-semibold
            {{ match($card['color']) {
                'teal'   => 'text-teal-600',
                'blue'   => 'text-blue-600',
                'purple' => 'text-purple-600',
                'red'    => 'text-red-600',
                default  => 'text-gray-800',
            } }}">
            {{ $card['value'] }}
        </p>
    </div>
    @endforeach
</div>

{{-- Grafik --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">

    {{-- Tren 6 bulan --}}
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <h2 class="text-sm font-medium text-gray-700 mb-4">
            Tren status gizi — 6 bulan terakhir
        </h2>
        <div class="relative h-56">
            <canvas id="chartTren"></canvas>
        </div>
    </div>

    {{-- Distribusi --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <h2 class="text-sm font-medium text-gray-700 mb-4">
            Distribusi bulan ini
        </h2>
        <div class="relative h-56 flex items-center justify-center">
            <canvas id="chartDistribusi"></canvas>
        </div>
    </div>
</div>

{{-- Tabel per posyandu --}}
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-sm font-medium text-gray-700">Rekap per posyandu</h2>
        <a href="{{ route('admin.laporan.index') }}"
           class="text-xs text-teal-600 hover:text-teal-700 transition-colors">
            Lihat laporan lengkap →
        </a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Posyandu</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kelurahan</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Balita</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Diukur</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Stunting</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($perPosyandu as $p)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5 text-gray-800 font-medium">{{ $p['nama'] }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $p['kelurahan'] }}</td>
                <td class="px-5 py-3.5 text-center text-gray-700">{{ $p['total_balita'] }}</td>
                <td class="px-5 py-3.5 text-center text-teal-600 font-medium">{{ $p['diukur'] }}</td>
                <td class="px-5 py-3.5 text-center">
                    @if($p['stunting'] > 0)
                        <span class="px-2 py-0.5 rounded-full text-xs bg-red-50 text-red-600">
                            {{ $p['stunting'] }}
                        </span>
                    @else
                        <span class="text-gray-400">0</span>
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
                backgroundColor: '#0F766E30',
                borderColor: '#0F766E',
                borderWidth: 1.5,
                borderRadius: 4,
            },
            {
                label: 'Stunting',
                data: grafikData.map(d => d.stunting),
                backgroundColor: '#DC262630',
                borderColor: '#DC2626',
                borderWidth: 1.5,
                borderRadius: 4,
            },
            {
                label: 'Gizi Buruk/Kurang',
                data: grafikData.map(d => d.gizi_buruk),
                backgroundColor: '#D9770630',
                borderColor: '#D97706',
                borderWidth: 1.5,
                borderRadius: 4,
            },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#4B5563', font: { size: 11 } } } },
        scales: {
            x: { ticks: { color: '#6B7280', font: { size: 11 } }, grid: { color: '#F3F4F6' } },
            y: { ticks: { color: '#6B7280', font: { size: 11 } }, grid: { color: '#F3F4F6' }, beginAtZero: true },
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
    normal: '#0F766E', stunting: '#D97706', severely_stunting: '#DC2626',
    gizi_buruk: '#9A3412', gizi_kurang: '#FBBF24', gizi_lebih: '#3B82F6', obesitas: '#7C3AED',
};
const keys   = Object.keys(distribusi);
new Chart(document.getElementById('chartDistribusi'), {
    type: 'doughnut',
    data: {
        labels: keys.map(k => statusLabels[k] ?? k),
        datasets: [{
            data: keys.map(k => distribusi[k]),
            backgroundColor: keys.map(k => colors[k] ?? '#9CA3AF'),
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom', labels: { color: '#4B5563', font: { size: 11 }, padding: 12 } }
        }
    }
});
</script>
@endpush
</x-app-layout>