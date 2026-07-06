<x-app-layout>
<x-slot name="title">Detail — {{ $balita->nama }}</x-slot>

<div class="mb-6">
    <a href="{{ route('kader.balita.index') }}"
       class="inline-flex items-center gap-1.5 text-xs text-ink-2
              hover:text-brand-400 transition-colors mb-3">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke daftar balita
    </a>

    <div class="flex items-start justify-between mt-1">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center
                        text-base font-600 shrink-0
                        {{ $balita->jenis_kelamin === 'L'
                            ? 'bg-blue-50 text-blue-500'
                            : 'bg-pink-50 text-pink-500' }}">
                {{ strtoupper(substr($balita->nama, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-600 text-ink">{{ $balita->nama }}</h1>
                <p class="text-sm text-ink-2 mt-0.5">
                    {{ $balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    · {{ $balita->usiaBulanPada() }} bulan
                    · {{ $balita->tanggal_lahir->format('d M Y') }}
                </p>
            </div>
        </div>

        {{-- ✅ Tombol aksi — di LUAR form hapus --}}
        <div class="flex gap-2 flex-wrap justify-end">
            <a href="{{ route('kader.balita.kms', $balita) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2
                      bg-pink-50 hover:bg-pink-100 text-pink-600
                      text-sm font-500 rounded-xl border border-pink-200
                      transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                </svg>
                Cetak KMS
            </a>
            <a href="{{ route('kader.balita.edit', $balita) }}"
               class="btn-secondary">
                Edit data
            </a>
            <a href="{{ route('kader.balita.pengukuran.create', $balita) }}"
               class="btn-primary">
                + Input pengukuran
            </a>
        </div>
    </div>
</div>

{{-- Info cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @php $terakhir = $balita->pengukuranTerakhir @endphp

    <div class="card p-4">
        <p class="text-xs text-ink-3 uppercase tracking-wider mb-1">Berat terakhir</p>
        <p class="text-2xl font-600 text-ink">
            {{ $terakhir?->berat_badan ?? '—' }}
            <span class="text-sm font-400 text-ink-2">kg</span>
        </p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-ink-3 uppercase tracking-wider mb-1">Tinggi terakhir</p>
        <p class="text-2xl font-600 text-ink">
            {{ $terakhir?->tinggi_badan ?? '—' }}
            <span class="text-sm font-400 text-ink-2">cm</span>
        </p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-ink-3 uppercase tracking-wider mb-1">Z-score TB/U</p>
        <p class="text-2xl font-600
            {{ ($terakhir?->zscore_tbu ?? 0) < -2 ? 'text-red-500' : 'text-ink' }}">
            {{ $terakhir?->zscore_tbu ?? '—' }}
        </p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-ink-3 uppercase tracking-wider mb-1">Status gizi</p>
        @if($terakhir)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-500 mt-1
                {{ match($terakhir->status_color) {
                    'green'  => 'bg-emerald-50 text-emerald-600',
                    'red'    => 'bg-red-50 text-red-500',
                    'yellow' => 'bg-amber-50 text-amber-600',
                    'orange' => 'bg-orange-50 text-orange-600',
                    default  => 'bg-surface-2 text-ink-2',
                } }}">
                {{ $terakhir->status_label }}
            </span>
        @else
            <p class="text-ink-3 text-sm mt-1">Belum diukur</p>
        @endif
    </div>
</div>

{{-- Alert stunting --}}
@if($terakhir && in_array($terakhir->status_gizi, ['stunting','severely_stunting','gizi_buruk']))
<div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 flex items-start gap-3">
    <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>
    <div>
        <p class="text-sm font-600 text-red-600">Perhatian — {{ $terakhir->status_label }}</p>
        <p class="text-xs text-red-400 mt-0.5">
            Terdeteksi {{ $terakhir->status_label }} pada
            {{ $terakhir->tanggal_ukur->format('d M Y') }}.
            Segera laporkan ke petugas puskesmas.
        </p>
    </div>
</div>
@endif

{{-- Grafik pertumbuhan --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <div class="card p-5">
        <h2 class="text-sm font-600 text-ink mb-1">Grafik BB/U</h2>
        <div class="flex items-center gap-4 mb-3">
            <span class="flex items-center gap-1.5 text-xs text-ink-2">
                <span class="w-3 h-2 rounded-sm bg-emerald-300 inline-block"></span>Normal
            </span>
            <span class="flex items-center gap-1.5 text-xs text-ink-2">
                <span class="w-3 h-2 rounded-sm bg-amber-300 inline-block"></span>Risiko
            </span>
            <span class="flex items-center gap-1.5 text-xs text-ink-2">
                <span class="w-3 h-2 rounded-sm bg-red-300 inline-block"></span>Gizi buruk
            </span>
        </div>
        <div class="relative h-56 bg-white rounded-xl p-2 border border-line">
            <canvas id="chartBBU"></canvas>
        </div>
    </div>
    <div class="card p-5">
        <h2 class="text-sm font-600 text-ink mb-1">Grafik TB/U</h2>
        <div class="flex items-center gap-4 mb-3">
            <span class="flex items-center gap-1.5 text-xs text-ink-2">
                <span class="w-3 h-2 rounded-sm bg-emerald-300 inline-block"></span>Normal
            </span>
            <span class="flex items-center gap-1.5 text-xs text-ink-2">
                <span class="w-3 h-2 rounded-sm bg-amber-300 inline-block"></span>Stunting
            </span>
            <span class="flex items-center gap-1.5 text-xs text-ink-2">
                <span class="w-3 h-2 rounded-sm bg-red-300 inline-block"></span>Severely stunting
            </span>
        </div>
        <div class="relative h-56 bg-white rounded-xl p-2 border border-line">
            <canvas id="chartTBU"></canvas>
        </div>
    </div>
</div>

{{-- Riwayat pengukuran --}}
<div class="card overflow-hidden mb-4">
    <div class="flex items-center justify-between px-5 py-4 border-b border-line">
        <h2 class="text-sm font-600 text-ink">Riwayat pengukuran</h2>
        <span class="text-xs text-ink-2">{{ $pengukuran->total() }} sesi</span>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-line bg-surface-2">
                <th class="text-left px-5 py-3 text-xs font-500 text-ink-2 uppercase tracking-wider">Tanggal</th>
                <th class="text-center px-4 py-3 text-xs font-500 text-ink-2 uppercase tracking-wider">Usia</th>
                <th class="text-center px-4 py-3 text-xs font-500 text-ink-2 uppercase tracking-wider">BB (kg)</th>
                <th class="text-center px-4 py-3 text-xs font-500 text-ink-2 uppercase tracking-wider">TB (cm)</th>
                <th class="text-center px-4 py-3 text-xs font-500 text-ink-2 uppercase tracking-wider hidden lg:table-cell">Z BB/U</th>
                <th class="text-center px-4 py-3 text-xs font-500 text-ink-2 uppercase tracking-wider hidden lg:table-cell">Z TB/U</th>
                <th class="text-left px-4 py-3 text-xs font-500 text-ink-2 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-line">
            @forelse($pengukuran as $p)
            <tr class="hover:bg-surface-2 transition-colors">
                <td class="px-5 py-3.5 text-ink font-500 text-sm">
                    {{ $p->tanggal_ukur->format('d M Y') }}
                </td>
                <td class="px-4 py-3.5 text-center text-ink-2 text-sm">
                    {{ $p->usia_bulan }} bln
                </td>
                <td class="px-4 py-3.5 text-center text-ink text-sm">{{ $p->berat_badan }}</td>
                <td class="px-4 py-3.5 text-center text-ink text-sm">{{ $p->tinggi_badan }}</td>
                <td class="px-4 py-3.5 text-center hidden lg:table-cell text-sm
                    {{ ($p->zscore_bbu ?? 0) < -2 ? 'text-red-500 font-500' : 'text-ink-2' }}">
                    {{ $p->zscore_bbu ?? '—' }}
                </td>
                <td class="px-4 py-3.5 text-center hidden lg:table-cell text-sm
                    {{ ($p->zscore_tbu ?? 0) < -2 ? 'text-red-500 font-500' : 'text-ink-2' }}">
                    {{ $p->zscore_tbu ?? '—' }}
                </td>
                <td class="px-4 py-3.5">
                    <span class="badge-{{ match($p->status_color) {
                        'green'  => 'success',
                        'red'    => 'danger',
                        'yellow' => 'warning',
                        'orange' => 'warning',
                        default  => 'neutral',
                    } }}">
                        {{ $p->status_label }}
                    </span>
                </td>
                {{-- ✅ Form hapus berdiri sendiri, tidak membungkus elemen lain --}}
                <td class="px-4 py-3.5 text-right">
                    <form action="{{ route('kader.pengukuran.destroy', $p->id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus data pengukuran ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-xs text-ink-3 hover:text-red-500 transition-colors">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-12 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-brand-50 flex items-center
                                justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12h3.75M9 15h3.75m-7.5 4.5h15M4.5 3h15M5.25 3v18m13.5-18v18"/>
                        </svg>
                    </div>
                    <p class="text-sm text-ink-2 mb-1">Belum ada data pengukuran</p>
                    <a href="{{ route('kader.balita.pengukuran.create', $balita) }}"
                       class="text-sm text-brand-400 hover:text-brand-500 font-500">
                        Input sekarang →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($pengukuran->hasPages())
    <div class="px-5 py-3 border-t border-line">
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

function getRef(tbl, u) {
    const keys = Object.keys(tbl).map(Number).sort((a,b)=>a-b);
    return tbl[keys.reduce((p,c)=>Math.abs(c-u)<Math.abs(p-u)?c:p)];
}

const bbu2 = {0:2.1,3:4.5,6:6.0,9:7.2,12:8.1,18:9.4,24:10.5,36:12.2,48:13.7,60:15.3};
const bbu3 = {0:1.8,3:3.9,6:5.1,9:6.1,12:6.9,18:8.0,24:8.9,36:10.4,48:11.7,60:13.0};
const tbu2 = {0:46.3,3:57.3,6:63.6,9:68.0,12:71.5,18:76.9,24:81.7,36:88.7,48:94.9,60:100.7};
const tbu3 = {0:43.6,3:54.0,6:60.0,9:64.0,12:67.0,18:72.0,24:76.5,36:83.0,48:88.5,60:93.5};

const ref2SD_BB  = usiaArr.map(u => getRef(bbu2, u));
const ref3SD_BB  = usiaArr.map(u => getRef(bbu3, u));
const ref2SD_TB  = usiaArr.map(u => getRef(tbu2, u));
const ref3SD_TB  = usiaArr.map(u => getRef(tbu3, u));
const bbTop      = usiaArr.map(() => Math.max(...bbData, ...ref2SD_BB) + 2);
const tbTop      = usiaArr.map(() => Math.max(...tbData, ...ref2SD_TB) + 5);
const tbBottom   = usiaArr.map(() => Math.max(0, Math.min(...ref3SD_TB) - 10));

const baseOpts = (yMin) => ({
    responsive: true,
    maintainAspectRatio: false,
    interaction: { intersect: false, mode: 'index' },
    plugins: {
        legend: {
            labels: {
                color: '#374151', font: { size: 10 }, boxWidth: 14,
                filter: (i) => !['Top','Bottom'].includes(i.text),
            }
        }
    },
    scales: {
        x: { ticks: { color: '#6b7280', font: { size: 10 } }, grid: { color: '#e5e7eb' } },
        y: { ticks: { color: '#6b7280', font: { size: 10 } }, grid: { color: '#e5e7eb' }, min: yMin },
    }
});

// BB/U
new Chart(document.getElementById('chartBBU'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            { label:'Top',            data: bbTop,     borderColor:'transparent', pointRadius:0, fill:false },
            { label:'Normal',         data: ref2SD_BB, borderColor:'transparent', backgroundColor:'rgba(134,239,172,0.5)', pointRadius:0, fill:0 },
            { label:'Risiko',         data: ref3SD_BB, borderColor:'transparent', backgroundColor:'rgba(253,224,71,0.5)',  pointRadius:0, fill:1 },
            { label:'Bottom',         data: usiaArr.map(()=>0), borderColor:'transparent', backgroundColor:'rgba(252,165,165,0.5)', pointRadius:0, fill:2 },
            { label:'Berat badan (kg)', data: bbData,  borderColor:'#059669', backgroundColor:'#059669', borderWidth:2.5, pointRadius:4, tension:0.3, fill:false },
        ]
    },
    options: baseOpts(0),
});

// TB/U
new Chart(document.getElementById('chartTBU'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            { label:'Top',              data: tbTop,     borderColor:'transparent', pointRadius:0, fill:false },
            { label:'Normal',           data: ref2SD_TB, borderColor:'transparent', backgroundColor:'rgba(134,239,172,0.5)', pointRadius:0, fill:0 },
            { label:'Stunting',         data: ref3SD_TB, borderColor:'transparent', backgroundColor:'rgba(253,224,71,0.5)',  pointRadius:0, fill:1 },
            { label:'Bottom',           data: tbBottom,  borderColor:'transparent', backgroundColor:'rgba(252,165,165,0.5)', pointRadius:0, fill:2 },
            { label:'Tinggi badan (cm)', data: tbData,   borderColor:'#1d4ed8', backgroundColor:'#1d4ed8', borderWidth:2.5, pointRadius:4, tension:0.3, fill:false },
        ]
    },
    options: baseOpts(tbBottom[0]),
});
</script>
@endpush
</x-app-layout>