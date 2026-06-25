<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Puskesmas',
            'email'    => 'admin@puskesmas.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'       => 'Kader Melati',
            'email'      => 'kader@posyandu.test',
            'password'   => Hash::make('password'),
            'role'       => 'kader',
            'posyandu_id'=> 1,
        ]);
    }
}