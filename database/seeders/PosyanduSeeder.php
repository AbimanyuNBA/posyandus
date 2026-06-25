<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Posyandu;

class PosyanduSeeder extends Seeder
{
    public function run(): void
    {
        Posyandu::insert([
            ['nama' => 'Posyandu Melati', 'kelurahan' => 'Belakang Padang', 'kecamatan' => 'Belakang Padang', 'kota' => 'Batam', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Posyandu Mawar', 'kelurahan' => 'Batu Aji', 'kecamatan' => 'Batu Aji', 'kota' => 'Batam', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}