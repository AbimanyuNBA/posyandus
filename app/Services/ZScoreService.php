<?php
namespace App\Services;

use App\Models\ReferensiWho;

class ZScoreService
{
    public function hitungZScore(float $nilai, string $indikator, string $jenisKelamin, int $usiaBulan): ?float
    {
        $ref = ReferensiWho::where('jenis_kelamin', $jenisKelamin)
            ->where('indikator', $indikator)
            ->where('usia_bulan', $usiaBulan)
            ->first();

        if (!$ref) {
            $ref = ReferensiWho::where('jenis_kelamin', $jenisKelamin)
                ->where('indikator', $indikator)
                ->orderByRaw('ABS(usia_bulan - ?)', [$usiaBulan])
                ->first();
        }

        if (!$ref) return null;

        $L = (float) $ref->l_value;
        $M = (float) $ref->m_value;
        $S = (float) $ref->s_value;

        if (abs($L) < 0.0001) {
            return log($nilai / $M) / $S;
        }

        return (pow($nilai / $M, $L) - 1) / ($L * $S);
    }

    public function analisis(float $bb, float $tb, string $jk, int $usiaBulan): array
    {
        $zBbu  = $this->hitungZScore($bb, 'BB/U', $jk, $usiaBulan);
        $zTbu  = $this->hitungZScore($tb, 'TB/U', $jk, $usiaBulan);
        $zBbtb = $this->hitungZScore($bb, 'BB/TB', $jk, $usiaBulan);

        $status = $this->enentukanStatus($zBbu, $zTbu, $zBbtb);

        return [
            'zscore_bbu'  => $zBbu  ? round($zBbu, 3)  : null,
            'zscore_tbu'  => $zTbu  ? round($zTbu, 3)  : null,
            'zscore_bbtb' => $zBbtb ? round($zBbtb, 3) : null,
            'status_gizi' => $status,
        ];
    }

    private function enentukanStatus(?float $zBbu, ?float $zTbu, ?float $zBbtb): string
    {
        if ($zTbu !== null) {
            if ($zTbu < -3) return 'severely_stunting';
            if ($zTbu < -2) return 'stunting';
        }

        if ($zBbu !== null) {
            if ($zBbu < -3) return 'gizi_buruk';
            if ($zBbu < -2) return 'gizi_kurang';
            if ($zBbu > 3)  return 'obesitas';
            if ($zBbu > 2)  return 'gizi_lebih';
        }

        return 'normal';
    }
}