<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengukuran extends Model
{
    protected $fillable = [
        'balita_id', 'user_id', 'tanggal_ukur',
        'berat_badan', 'tinggi_badan', 'usia_bulan',
        'zscore_bbu', 'zscore_tbu', 'zscore_bbtb',
        'status_gizi', 'catatan',
    ];

    protected $casts = ['tanggal_ukur' => 'date'];

    protected $table = 'pengukuran';

    public function balita()
    {
        return $this->belongsTo(Balita::class);
    }

    public function kader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status_gizi) {
            'normal'             => 'Normal',
            'gizi_kurang'        => 'Gizi Kurang',
            'gizi_buruk'         => 'Gizi Buruk',
            'gizi_lebih'         => 'Gizi Lebih',
            'obesitas'           => 'Obesitas',
            'stunting'           => 'Stunting',
            'severely_stunting'  => 'Severely Stunting',
            default              => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status_gizi) {
            'normal'            => 'green',
            'gizi_kurang'       => 'yellow',
            'gizi_buruk'        => 'red',
            'gizi_lebih'        => 'orange',
            'obesitas'          => 'red',
            'stunting'          => 'orange',
            'severely_stunting' => 'red',
            default             => 'gray',
        };
    }
}