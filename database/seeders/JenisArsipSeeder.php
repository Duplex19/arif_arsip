<?php

namespace Database\Seeders;

use App\Models\JenisArsip;
use Illuminate\Database\Seeder;

class JenisArsipSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            ['nama_jenis' => 'Surat Keputusan (SK)', 'kode_jenis' => 'SK'],
            ['nama_jenis' => 'Nota Dinas', 'kode_jenis' => 'ND'],
            ['nama_jenis' => 'Surat Masuk', 'kode_jenis' => 'SM'],
            ['nama_jenis' => 'Surat Keluar', 'kode_jenis' => 'SKL'],
            ['nama_jenis' => 'Laporan', 'kode_jenis' => 'LAP'],
            ['nama_jenis' => 'Instruksi Kerja', 'kode_jenis' => 'IK'],
        ];

        foreach ($jenis as $item) {
            JenisArsip::create($item);
        }
    }
}
