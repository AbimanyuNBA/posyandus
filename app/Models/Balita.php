<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon; // <-- Pastikan Carbon di-import

class Balita extends Model
{
    use SoftDeletes;

    protected $table = 'balita'; 

    protected $fillable = [
        'posyandu_id', 'nama', 'nik', 'tanggal_lahir',
        'jenis_kelamin', 'nama_ortu', 'no_hp_ortu', 'alamat',
    ];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function pengukuran()
    {
        return $this->hasMany(Pengukuran::class)->orderBy('tanggal_ukur', 'desc');
    }

    public function pengukuranTerakhir()
    {
        return $this->hasOne(Pengukuran::class)->latestOfMany('tanggal_ukur');
    }

    // Hitung usia dalam bulan dari tanggal lahir ke tanggal tertentu
    public function usiaBulanPada(?string $tanggal = null): int
    {
        $tgl = $tanggal ? \Carbon\Carbon::parse($tanggal) : now();
        return (int) $this->tanggal_lahir->diffInMonths($tgl);
    }
}