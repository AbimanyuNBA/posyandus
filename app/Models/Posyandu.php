<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    protected $table = 'posyandu';
    protected $fillable = ['nama', 'alamat', 'kelurahan', 'kecamatan', 'kota', 'kontak'];

    public function kaders()
    {
        return $this->hasMany(User::class);
    }

    public function balita()
    {
        return $this->hasMany(Balita::class);
    }
}