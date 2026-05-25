<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $bidang = [
            'Sekretariat',
            'Bidang Ketersediaan Pangan',
            'Bidang Distribusi Pangan',
            'Bidang Konsumsi Pangan',
        ];

        foreach ($bidang as $nama) {
            Bidang::create(['nama_bidang' => $nama]);
        }
    }
}
