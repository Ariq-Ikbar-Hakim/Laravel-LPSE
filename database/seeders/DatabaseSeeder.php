<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'nip' => '1234567890123456',
            'nama' => 'Admin LPSE',
            'email' => 'admin@lpse.test',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'jabatan_aktif' => 'admin',
            'status_aktif' => 1,
        ]);

        User::factory()->create([
            'nip' => '1111111111111111',
            'nama' => 'Pejabat Pembuat Komitmen',
            'email' => 'ppk@lpse.test',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'jabatan_aktif' => 'PPK',
            'status_aktif' => 1,
        ]);

        User::factory()->create([
            'nip' => '2222222222222222',
            'nama' => 'Pejabat Pengadaan',
            'email' => 'pp@lpse.test',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'jabatan_aktif' => 'PP',
            'status_aktif' => 1,
        ]);

        User::factory()->create([
            'nip' => '3333333333333333',
            'nama' => 'Pending User',
            'email' => 'pending@lpse.test',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'jabatan_aktif' => 'PPK',
            'status_aktif' => 0,
        ]);
    }
}
