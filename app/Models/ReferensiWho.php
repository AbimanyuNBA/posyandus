<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferensiWho extends Model
{
    protected $table = 'referensi_who';
    
    public $timestamps = false;
    
    protected $fillable = [
        'jenis_kelamin',
        'usia_bulan', 
        'indikator',
        'l_value',
        'm_value',
        's_value',
    ];
}