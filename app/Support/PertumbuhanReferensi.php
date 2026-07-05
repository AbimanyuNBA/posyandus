<?php

namespace App\Support;

/**
 * Helper untuk data referensi WHO (-2 SD & -3 SD) dan pembuatan URL grafik
 * (via QuickChart.io) yang dipakai bersama oleh export PDF & Excel.
 *
 * Catatan: tabel di bawah ini masih simplified/placeholder, sama seperti
 * yang sudah dipakai di show.blade.php. Untuk produksi sebaiknya diganti
 * dengan tabel WHO resmi per jenis kelamin & satuan usia (bulan) yang lengkap.
 */
class PertumbuhanReferensi
{
    protected static array $bbu2sd = [0=>2.1,3=>4.5,6=>6.0,9=>7.2,12=>8.1,18=>9.4,24=>10.5,36=>12.2,48=>13.7,60=>15.3];
    protected static array $bbu3sd = [0=>1.8,3=>3.9,6=>5.1,9=>6.1,12=>6.9,18=>8.0,24=>8.9,36=>10.4,48=>11.7,60=>13.0];
    protected static array $tbu2sd = [0=>46.3,3=>57.3,6=>63.6,9=>68.0,12=>71.5,18=>76.9,24=>81.7,36=>88.7,48=>94.9,60=>100.7];
    protected static array $tbu3sd = [0=>43.6,3=>54.0,6=>60.0,9=>64.0,12=>67.0,18=>72.0,24=>76.5,36=>83.0,48=>88.5,60=>93.5];

    protected static function lookup(array $usiaArr, array $tabel): array
    {
        $keys = array_keys($tabel);

        return array_map(function ($u) use ($keys, $tabel) {
            $nearest = $keys[0];
            foreach ($keys as $k) {
                if (abs($k - $u) < abs($nearest - $u)) {
                    $nearest = $k;
                }
            }
            return $tabel[$nearest];
        }, $usiaArr);
    }

    public static function refBBU2SD(array $usiaArr): array { return static::lookup($usiaArr, static::$bbu2sd); }
    public static function refBBU3SD(array $usiaArr): array { return static::lookup($usiaArr, static::$bbu3sd); }
    public static function refTBU2SD(array $usiaArr): array { return static::lookup($usiaArr, static::$tbu2sd); }
    public static function refTBU3SD(array $usiaArr): array { return static::lookup($usiaArr, static::$tbu3sd); }

    /**
     * Bangun URL gambar grafik (line chart) via QuickChart.io.
     * Dipakai untuk menyisipkan grafik statis ke PDF (dompdf) & Excel
     * karena keduanya tidak bisa merender Chart.js langsung.
     */
    public static function buildChartUrl(
        array $labels,
        string $dataLabel,
        array $data,
        array $ref2sd,
        array $ref3sd,
        string $lineColor,
        string $ref2Label = 'Batas -2 SD',
        string $ref3Label = 'Batas -3 SD'
    ): string {
        $config = [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => $dataLabel,
                        'data' => $data,
                        'borderColor' => $lineColor,
                        'backgroundColor' => $lineColor,
                        'fill' => false,
                        'borderWidth' => 2,
                        'pointRadius' => 3,
                        'tension' => 0.3,
                    ],
                    [
                        'label' => $ref2Label,
                        'data' => $ref2sd,
                        'borderColor' => '#F59E0B',
                        'borderDash' => [6, 4],
                        'fill' => false,
                        'borderWidth' => 1.5,
                        'pointRadius' => 0,
                    ],
                    [
                        'label' => $ref3Label,
                        'data' => $ref3sd,
                        'borderColor' => '#E2534A',
                        'borderDash' => [6, 4],
                        'fill' => false,
                        'borderWidth' => 1.5,
                        'pointRadius' => 0,
                    ],
                ],
            ],
            'options' => [
                'plugins' => ['legend' => ['labels' => ['font' => ['size' => 11]]]],
                'scales' => [
                    'y' => ['beginAtZero' => false],
                ],
            ],
        ];

        return 'https://quickchart.io/chart?' . http_build_query([
            'c' => json_encode($config),
            'width' => 480,
            'height' => 280,
            'backgroundColor' => 'white',
        ]);
    }
}