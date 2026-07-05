
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMS — {{ $balita->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #fff;
            color: #1A2B3C;
            font-size: 12px;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 10mm 12mm;
            background: #fff;
        }

        /* ── Header ── */
        .kms-header {
            display: flex;
            align-items: stretch;
            border: 2px solid #F9A8C9;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .header-logo {
            background: #FDF2F8;
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 120px;
            border-right: 2px solid #F9A8C9;
        }

        .kms-brand {
            font-size: 28px;
            font-weight: 700;
            color: #E91E8C;
            letter-spacing: -1px;
            line-height: 1;
        }

        .kms-brand-sub {
            font-size: 7px;
            font-weight: 600;
            color: #E91E8C;
            text-align: center;
            letter-spacing: 0.5px;
            line-height: 1.3;
            margin-top: 2px;
        }

        .kms-gender {
            font-size: 11px;
            font-weight: 700;
            margin-top: 6px;
            padding: 3px 10px;
            border-radius: 99px;
            background: #E91E8C;
            color: white;
        }

        .header-fields {
            flex: 1;
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }

        .field-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .field-label {
            font-size: 11px;
            color: #6B7A8D;
            min-width: 110px;
        }

        .field-value {
            font-size: 12px;
            font-weight: 600;
            color: #1A2B3C;
            border-bottom: 1.5px solid #E8EDF2;
            flex: 1;
            padding-bottom: 2px;
        }

        /* ── Subtitle ── */
        .kms-subtitle {
            text-align: center;
            margin-bottom: 8px;
        }
        .kms-subtitle p:first-child {
            font-size: 12px;
            font-weight: 600;
            color: #1A2B3C;
        }
        .kms-subtitle p:last-child {
            font-size: 11px;
            color: #6B7A8D;
            font-style: italic;
        }

        /* ── Chart area ── */
        .chart-wrapper {
            border: 1.5px solid #CBD5E0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        canvas#kmsChart {
            display: block;
            width: 100% !important;
            height: 220px !important;
        }

        /* ── Data table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 10px;
        }

        .data-table th {
            background: #FDF2F8;
            color: #E91E8C;
            font-weight: 700;
            padding: 5px 4px;
            text-align: center;
            border: 1px solid #F9A8C9;
            font-size: 9px;
        }

        .data-table .row-label {
            background: #F8FAFC;
            font-weight: 600;
            color: #1A2B3C;
            padding: 5px 6px;
            border: 1px solid #E8EDF2;
            white-space: nowrap;
            font-size: 9.5px;
        }

        .data-table td {
            padding: 5px 4px;
            text-align: center;
            border: 1px solid #E8EDF2;
            color: #1A2B3C;
        }

        .data-table td.nt-n {
            background: #D1FAE5;
            color: #065F46;
            font-weight: 700;
        }

        .data-table td.nt-t {
            background: #FEE2E2;
            color: #991B1B;
            font-weight: 700;
        }

        /* ── Legend ── */
        .legend-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .legend-box {
            flex: 1;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 10px;
            line-height: 1.5;
        }

        .legend-n {
            background: #D1FAE5;
            border: 1.5px solid #6EE7B7;
        }

        .legend-t {
            background: #FEE2E2;
            border: 1.5px solid #FCA5A5;
        }

        .legend-title {
            font-weight: 700;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .legend-n .legend-title { color: #065F46; }
        .legend-t .legend-title { color: #991B1B; }

        /* ── Alert ── */
        .alert-rujuk {
            background: #FEF3C7;
            border: 2px solid #F59E0B;
            border-radius: 8px;
            padding: 8px 14px;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: 700;
            color: #92400E;
            text-align: center;
        }

        /* ── Status badge ── */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 600;
        }

        /* ── Footer ── */
        .kms-footer {
            margin-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #A8B4C0;
        }

        /* ── Z-score summary ── */
        .zscore-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .zscore-card {
            border: 1.5px solid #E8EDF2;
            border-radius: 8px;
            padding: 8px;
            text-align: center;
        }

        .zscore-card .label {
            font-size: 9px;
            color: #6B7A8D;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .zscore-card .value {
            font-size: 18px;
            font-weight: 700;
            line-height: 1;
        }

        .zscore-card .sublabel {
            font-size: 9px;
            color: #6B7A8D;
            margin-top: 2px;
        }

        /* ── Print styles ── */
        @media print {
            body { background: white; }
            .page { margin: 0; padding: 8mm 10mm; }
            .no-print { display: none !important; }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }

        /* ── Print button (screen only) ── */
        .print-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: #1A2B3C;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            box-shadow: 0 2px 12px rgba(0,0,0,0.2);
        }

        .print-bar .info {
            font-size: 13px;
            font-weight: 500;
        }

        .print-bar .info span {
            color: #9FE1CB;
            font-weight: 600;
        }

        .btn-print {
            background: #2BB5A0;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-print:hover { background: #219E8B; }

        .btn-back {
            background: transparent;
            color: #9FE1CB;
            border: 1px solid #9FE1CB;
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            text-decoration: none;
        }

        .btn-back:hover { background: rgba(159,225,203,0.1); }

        @media screen {
            body { background: #F0F4F8; }
            .page { margin: 60px auto 30px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); }
        }
    </style>
</head>
<body>

{{-- Print bar (screen only) --}}
<div class="print-bar no-print">
    <div class="info">
        KMS Digital —
        <span>{{ $balita->nama }}</span>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <a href="{{ route('kader.balita.show', $balita) }}" class="btn-back">
            ← Kembali
        </a>
        <button class="btn-print" onclick="window.print()">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak / Simpan PDF
        </button>
    </div>
</div>

<div class="page">

    {{-- ── Header KMS ── --}}
    <div class="kms-header">
        <div class="header-logo">
            <div class="kms-brand">KMS</div>
            <div class="kms-brand-sub">KARTU MENUJU<br>SEHAT</div>
            <div class="kms-gender">
                {{ $balita->jenis_kelamin === 'L' ? 'Untuk Laki-laki' : 'Untuk Perempuan' }}
            </div>
        </div>
        <div class="header-fields">
            <div class="field-row">
                <span class="field-label">Nama Anak</span>
                <span class="field-value">{{ $balita->nama }}</span>
            </div>
            <div class="field-row">
                <span class="field-label">Tanggal Lahir</span>
                <span class="field-value">{{ $balita->tanggal_lahir->translatedFormat('d F Y') }}</span>
            </div>
            <div class="field-row">
                <span class="field-label">Nama Orang Tua</span>
                <span class="field-value">{{ $balita->nama_ortu }}</span>
            </div>
            <div class="field-row">
                <span class="field-label">Nama Posyandu</span>
                <span class="field-value">{{ $balita->posyandu->nama }}</span>
            </div>
        </div>
    </div>

    {{-- ── Subtitle ── --}}
    <div class="kms-subtitle">
        <p>Timbanglah Anak Anda Setiap Bulan</p>
        <p>Anak Sehat, Tambah Umur, Tambah Berat, Tambah Pandai</p>
    </div>

    {{-- ── Z-score summary ── --}}
    @if($pengukuran->count() > 0)
    @php $terakhir = $pengukuran->last(); @endphp
    <div class="zscore-summary">
        <div class="zscore-card">
            <div class="label">Berat Terakhir</div>
            <div class="value" style="color:#1A2B3C">{{ $terakhir->berat_badan }} <span style="font-size:12px;font-weight:400">kg</span></div>
            <div class="sublabel">{{ $terakhir->tanggal_ukur->format('d/m/Y') }}</div>
        </div>
        <div class="zscore-card">
            <div class="label">Z-Score TB/U</div>
            <div class="value" style="color:{{ ($terakhir->zscore_tbu ?? 0) < -2 ? '#E2534A' : '#2BB5A0' }}">
                {{ $terakhir->zscore_tbu ?? '—' }}
            </div>
            <div class="sublabel">Tinggi / Usia</div>
        </div>
        <div class="zscore-card" style="border-color:{{ match($terakhir->status_color) { 'green'=>'#6EE7B7','red'=>'#FCA5A5','orange'=>'#FCD34D','yellow'=>'#FDE68A',default=>'#E8EDF2'} }}">
            <div class="label">Status Gizi</div>
            <div class="value" style="font-size:14px;color:{{ match($terakhir->status_color) {'green'=>'#065F46','red'=>'#991B1B','orange'=>'#C2410C','yellow'=>'#92400E',default=>'#1A2B3C'} }}">
                {{ $terakhir->status_label }}
            </div>
            <div class="sublabel">Pengukuran terakhir</div>
        </div>
    </div>
    @endif

    {{-- ── Grafik BB/U ── --}}
    <div class="chart-wrapper">
        <canvas id="kmsChart"></canvas>
    </div>

    {{-- ── Tabel data penimbangan ── --}}
    @php
        $maxBulan = max(24, $pengukuran->max('usia_bulan') ?? 24);
        $range = range(0, min($maxBulan, 59));
        // Bagi jadi 2 baris jika > 24 bulan
        $chunks = array_chunk($range, 25);
    @endphp

    @foreach($chunks as $chunkIdx => $bulanRange)
    <table class="data-table" style="margin-bottom:6px">
        <thead>
            <tr>
                <th style="width:90px">Umur (bln)</th>
                @foreach($bulanRange as $bln)
                    <th>{{ $bln }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- BB --}}
            <tr>
                <td class="row-label">BB (kg)</td>
                @foreach($bulanRange as $bln)
                    <td>{{ isset($plotData[$bln]) ? number_format($plotData[$bln]['bb'], 1) : '' }}</td>
                @endforeach
            </tr>
            {{-- N/T --}}
            <tr>
                <td class="row-label">N / T</td>
                @foreach($bulanRange as $bln)
                    @if(isset($plotData[$bln]))
                        <td class="{{ $plotData[$bln]['nt'] === 'N' ? 'nt-n' : 'nt-t' }}">
                            {{ $plotData[$bln]['nt'] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
        </tbody>
    </table>
    @endforeach

    {{-- ── Legend ── --}}
    <div class="legend-row">
        <div class="legend-box legend-n">
            <div class="legend-title">NAIK (N)</div>
            Grafik BB mengikuti garis pertumbuhan atau kenaikan BB sama dengan KBM (Kenaikan BB Minimal) atau lebih
        </div>
        <div class="legend-box legend-t">
            <div class="legend-title">TIDAK NAIK (T)</div>
            Grafik BB mendatar atau menurun memotong garis pertumbuhan dibawahnya atau kenaikan BB kurang dari KBM
        </div>
    </div>

    {{-- ── Alert rujuk ── --}}
    @if($pengukuran->whereIn('status_gizi', ['gizi_buruk','severely_stunting'])->count() > 0)
    <div class="alert-rujuk">
        ⚠️ Rujuk ke petugas kesehatan bila tidak naik 2 kali berturut-turut atau BGM
    </div>
    @endif

    {{-- ── Footer ── --}}
    <div class="kms-footer">
        Dicetak via Sistem Informasi Monitoring Stunting · {{ $balita->posyandu->nama }} · {{ now()->format('d/m/Y H:i') }}
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const plotData  = @json($plotData);
const jk        = "{{ $balita->jenis_kelamin }}";
const maxBulan  = {{ min($pengukuran->max('usia_bulan') ?? 24, 59) }};
const labels    = Array.from({ length: maxBulan + 1 }, (_, i) => i);

// ── Referensi WHO -3SD, -2SD, median, +2SD (BB/U) ──
// Simplified reference curves — sesuaikan dengan data LMS lengkap
const refWHO = {
    L: {
        neg3: [1.7,2.4,3.4,4.2,4.9,5.5,6.0,6.4,6.8,7.1,7.4,7.6,7.8,8.0,8.2,8.4,8.5,8.7,8.8,8.9,9.1,9.2,9.3,9.4,9.5,9.7,9.8,9.9,10.0,10.1,10.2,10.3,10.4,10.5,10.6,10.7,10.8,10.9,11.0,11.1,11.2,11.3,11.4,11.5,11.6,11.7,11.8,11.9,12.0,12.1,12.2,12.3,12.4,12.5,12.6,12.7,12.8,12.9,13.0,13.1],
        neg2: [2.1,2.9,3.9,4.9,5.6,6.3,6.9,7.3,7.7,8.1,8.4,8.6,8.9,9.1,9.3,9.5,9.7,9.8,10.0,10.1,10.3,10.4,10.6,10.7,10.8,11.0,11.1,11.2,11.4,11.5,11.6,11.7,11.9,12.0,12.1,12.2,12.4,12.5,12.6,12.7,12.9,13.0,13.1,13.2,13.3,13.5,13.6,13.7,13.8,14.0,14.1,14.2,14.3,14.5,14.6,14.7,14.8,14.9,15.1,15.2],
        med:  [3.3,4.5,5.6,6.4,7.0,7.5,7.9,8.3,8.6,9.0,9.2,9.4,9.6,9.9,10.1,10.3,10.5,10.7,10.9,11.1,11.3,11.5,11.7,11.9,12.1,12.4,12.5,12.7,12.9,13.1,13.3,13.5,13.7,13.8,14.0,14.2,14.4,14.6,14.7,14.9,15.1,15.3,15.5,15.7,15.9,16.1,16.3,16.4,16.6,16.8,17.0,17.2,17.4,17.6,17.8,18.0,18.2,18.4,18.6,18.8],
        pos2: [4.4,5.8,7.1,8.0,8.7,9.3,9.8,10.3,10.7,11.0,11.4,11.7,12.0,12.3,12.6,12.8,13.1,13.4,13.6,13.9,14.1,14.4,14.7,14.9,15.2,15.6,15.8,16.1,16.3,16.6,16.8,17.1,17.3,17.6,17.8,18.1,18.3,18.6,18.8,19.1,19.3,19.6,19.9,20.1,20.4,20.7,21.0,21.2,21.5,21.8,22.1,22.4,22.7,23.0,23.3,23.6,23.9,24.2,24.5,24.9],
    },
    P: {
        neg3: [1.7,2.4,3.2,4.0,4.6,5.2,5.6,6.0,6.3,6.6,6.9,7.0,7.2,7.4,7.6,7.7,7.9,8.0,8.2,8.3,8.4,8.6,8.7,8.8,9.0,9.2,9.4,9.5,9.7,9.8,10.0,10.1,10.3,10.4,10.6,10.7,10.9,11.0,11.2,11.3,11.5,11.6,11.8,11.9,12.1,12.2,12.4,12.5,12.7,12.8,13.0,13.1,13.3,13.4,13.5,13.7,13.8,14.0,14.1,14.3],
        neg2: [2.0,2.8,3.7,4.5,5.1,5.7,6.1,6.5,6.9,7.2,7.5,7.7,7.9,8.1,8.3,8.5,8.7,8.9,9.0,9.2,9.4,9.5,9.7,9.9,10.0,10.2,10.4,10.5,10.7,10.8,11.0,11.1,11.3,11.4,11.6,11.7,11.9,12.0,12.2,12.3,12.5,12.6,12.8,13.0,13.1,13.3,13.5,13.6,13.8,14.0,14.1,14.3,14.5,14.6,14.8,15.0,15.1,15.3,15.5,15.6],
        med:  [3.2,4.2,5.1,5.8,6.4,6.9,7.3,7.6,7.9,8.2,8.5,8.7,8.9,9.2,9.4,9.6,9.8,10.0,10.2,10.4,10.6,10.9,11.1,11.3,11.5,11.7,11.9,12.1,12.3,12.5,12.7,12.9,13.1,13.3,13.5,13.7,13.9,14.1,14.3,14.5,14.7,14.9,15.2,15.4,15.6,15.8,16.0,16.3,16.5,16.7,16.9,17.2,17.4,17.6,17.8,18.1,18.3,18.5,18.7,19.0],
        pos2: [4.0,5.4,6.6,7.5,8.2,8.8,9.3,9.7,10.1,10.4,10.7,11.0,11.3,11.6,11.9,12.1,12.4,12.7,12.9,13.2,13.5,13.7,14.0,14.3,14.6,14.9,15.2,15.5,15.8,16.0,16.3,16.6,16.9,17.2,17.5,17.8,18.1,18.4,18.7,19.0,19.3,19.7,20.0,20.3,20.6,21.0,21.3,21.6,22.0,22.3,22.7,23.0,23.4,23.7,24.1,24.4,24.8,25.2,25.5,25.9],
    }
};

const ref = refWHO[jk] || refWHO['L'];
const sliceRef = (arr) => arr.slice(0, maxBulan + 1);

// Data aktual balita
const aktualData = labels.map(b => plotData[b] ? plotData[b].bb : null);

const ctx = document.getElementById('kmsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels,
        datasets: [
            // Zona +2 SD ke atas (kuning)
            {
                label: '+2 SD',
                data: sliceRef(ref.pos2),
                borderColor: '#D97706',
                borderWidth: 1,
                borderDash: [4, 3],
                pointRadius: 0,
                fill: false,
                tension: 0.4,
            },
            // Median
            {
                label: 'Median',
                data: sliceRef(ref.med),
                borderColor: '#059669',
                borderWidth: 1.5,
                pointRadius: 0,
                fill: false,
                tension: 0.4,
            },
            // -2 SD (batas bawah normal)
            {
                label: '-2 SD',
                data: sliceRef(ref.neg2),
                borderColor: '#F59E0B',
                borderWidth: 1.5,
                borderDash: [4, 3],
                pointRadius: 0,
                fill: {
                    target: '+2',    // isi antara -2 dan median
                    above: 'rgba(16,185,129,0.12)',
                },
                tension: 0.4,
            },
            // -3 SD (garis merah)
            {
                label: '-3 SD',
                data: sliceRef(ref.neg3),
                borderColor: '#EF4444',
                borderWidth: 1.5,
                borderDash: [3, 3],
                pointRadius: 0,
                fill: {
                    target: '-1',   // isi antara -3 dan -2 (zona kuning)
                    above: 'rgba(245,158,11,0.15)',
                },
                tension: 0.4,
            },
            // Data aktual balita
            {
                label: 'BB Anak',
                data: aktualData,
                borderColor: '#E91E8C',
                backgroundColor: '#E91E8C',
                borderWidth: 2.5,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#E91E8C',
                fill: false,
                tension: 0.3,
                spanGaps: false,
            },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { size: 10, family: "'DM Sans', sans-serif" },
                    padding: 10,
                    usePointStyle: true,
                    pointStyleWidth: 20,
                }
            },
            tooltip: {
                callbacks: {
                    title: (items) => `Usia ${items[0].label} bulan`,
                    label: (item) => {
                        if (item.dataset.label === 'BB Anak' && item.raw !== null) {
                            return `  BB: ${item.raw} kg`;
                        }
                        return `  ${item.dataset.label}: ${item.raw} kg`;
                    }
                }
            }
        },
        scales: {
            x: {
                title: { display: true, text: 'Umur (bulan)', font: { size: 10 }, color: '#6B7A8D' },
                ticks: { font: { size: 9 }, color: '#6B7A8D' },
                grid: { color: '#F0F4F8' },
            },
            y: {
                title: { display: true, text: 'Berat Badan (kg)', font: { size: 10 }, color: '#6B7A8D' },
                ticks: { font: { size: 9 }, color: '#6B7A8D' },
                grid: { color: '#F0F4F8' },
                min: 0,
                suggestedMax: 20,
            }
        }
    }
});
</script>

</body>
</html>