<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@arsip.test',
            'password' => Hash::make('password'),
            'bidang_id' => null,
            'role' => 'admin',
        ]);

        $sekretariat = Bidang::where('nama_bidang', 'Sekretariat')->first();
        $ketersediaan = Bidang::where('nama_bidang', 'Bidang Ketersediaan Pangan')->first();
        $distribusi = Bidang::where('nama_bidang', 'Bidang Distribusi Pangan')->first();
        $konsumsi = Bidang::where('nama_bidang', 'Bidang Konsumsi Pangan')->first();

        User::create([
            'username' => 'kepala.dinas',
            'email' => 'kepala@arsip.test',
            'password' => Hash::make('password'),
            'bidang_id' => $sekretariat->id,
            'role' => 'pimpinan',
        ]);

        User::create([
            'username' => 'staff.sekretariat',
            'email' => 'sekretariat@arsip.test',
            'password' => Hash::make('password'),
            'bidang_id' => $sekretariat->id,
            'role' => 'user',
        ]);

        User::create([
            'username' => 'staff.ketersediaan',
            'email' => 'ketersediaan@arsip.test',
            'password' => Hash::make('password'),
            'bidang_id' => $ketersediaan->id,
            'role' => 'user',
        ]);

        User::create([
            'username' => 'staff.distribusi',
            'email' => 'distribusi@arsip.test',
            'password' => Hash::make('password'),
            'bidang_id' => $distribusi->id,
            'role' => 'user',
        ]);

        User::create([
            'username' => 'staff.konsumsi',
            'email' => 'konsumsi@arsip.test',
            'password' => Hash::make('password'),
            'bidang_id' => $konsumsi->id,
            'role' => 'user',
        ]);
    }
}
