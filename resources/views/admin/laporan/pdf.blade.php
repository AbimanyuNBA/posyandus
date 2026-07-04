<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: sans-serif; font-size: 11px; color: #111; margin: 0; padding: 20px; }
  h1 { font-size: 16px; font-weight: 600; margin: 0 0 4px; }
  .sub { font-size: 11px; color: #555; margin-bottom: 20px; }
  .cards { display: flex; gap: 12px; margin-bottom: 20px; }
  .card { flex: 1; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; }
  .card-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; }
  .card-value { font-size: 22px; font-weight: 600; margin-top: 2px; }
  .c-teal { color: #0f766e; } .c-orange { color: #c2410c; } .c-red { color: #b91c1c; }
  table { width: 100%; border-collapse: collapse; }
  th { text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase;
       letter-spacing: .05em; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
  td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
  tr:nth-child(even) td { background: #f9fafb; }
  .badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: 500; }
  .b-green { background: #d1fae5; color: #065f46; }
  .b-red   { background: #fee2e2; color: #991b1b; }
  .b-orange{ background: #ffedd5; color: #c2410c; }
  .b-yellow{ background: #fef9c3; color: #854d0e; }
  .b-gray  { background: #f3f4f6; color: #374151; }
  .footer  { margin-top: 24px; font-size: 10px; color: #9ca3af; text-align: right; }
</style>
</head>
<body>
<h1>Laporan Rekap Status Gizi Balita</h1>
<div class="sub">
    {{ $posyandu }} &nbsp;·&nbsp; {{ $namaBulan }} {{ $tahun }}
    &nbsp;·&nbsp; Dicetak: {{ now()->format('d/m/Y H:i') }}
</div>

<div class="cards">
    <div class="card"><div class="card-label">Total pengukuran</div><div class="card-value">{{ $rekap['total'] }}</div></div>
    <div class="card"><div class="card-label">Normal</div><div class="card-value c-teal">{{ $rekap['normal'] }}</div></div>
    <div class="card"><div class="card-label">Stunting</div><div class="card-value c-orange">{{ $rekap['stunting'] }}</div></div>
    <div class="card"><div class="card-label">Gizi buruk/kurang</div><div class="card-value c-red">{{ $rekap['gizi_buruk'] }}</div></div>
</div>

<table>
    <thead>
        <tr>
            <th>Nama balita</th>
            <th>Posyandu</th>
            <th>Usia</th>
            <th>BB (kg)</th>
            <th>TB (cm)</th>
            <th>Z BB/U</th>
            <th>Z TB/U</th>
            <th>Status gizi</th>
            <th>Tanggal ukur</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengukuran as $p)
        @php
            $badgeClass = match($p->status_color) {
                'green'  => 'b-green',
                'red'    => 'b-red',
                'orange' => 'b-orange',
                'yellow' => 'b-yellow',
                default  => 'b-gray',
            };
        @endphp
        <tr>
            <td>{{ $p->balita->nama }}</td>
            <td>{{ $p->balita->posyandu->nama }}</td>
            <td>{{ $p->usia_bulan }} bln</td>
            <td>{{ $p->berat_badan }}</td>
            <td>{{ $p->tinggi_badan }}</td>
            <td>{{ $p->zscore_bbu ?? '—' }}</td>
            <td>{{ $p->zscore_tbu ?? '—' }}</td>
            <td><span class="badge {{ $badgeClass }}">{{ $p->status_label }}</span></td>
            <td>{{ $p->tanggal_ukur->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Sistem Informasi Monitoring Stunting — {{ config('app.name') }}
</div>
</body>
</html>