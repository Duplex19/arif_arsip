<?php

namespace Database\Factories;

use App\Models\JenisArsip;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisArsipFactory extends Factory
{
    protected $model = JenisArsip::class;

    public function definition(): array
    {
        static $counter = 0;
        $counter++;

        return [
            'nama_jenis' => 'Jenis Arsip '.fake()->word().' '.$counter,
            'kode_jenis' => strtoupper(fake()->lexify('???')),
        ];
    }
}
