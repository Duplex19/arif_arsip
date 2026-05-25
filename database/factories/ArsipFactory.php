<?php

namespace Database\Factories;

use App\Models\Arsip;
use App\Models\Bidang;
use App\Models\JenisArsip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArsipFactory extends Factory
{
    protected $model = Arsip::class;

    public function definition(): array
    {
        return [
            'nomor_arsip' => fake()->unique()->bothify('??-####/???/####'),
            'tgl_dilegalkan' => fake()->dateTimeBetween('-1 year', 'now'),
            'judul' => fake()->sentence(4),
            'jenis_arsip_id' => JenisArsip::factory(),
            'bidang_id' => Bidang::factory(),
            'file_path' => 'arsip/sample.pdf',
            'file_size' => fake()->numberBetween(1024, 5120000),
            'file_type' => fake()->randomElement(['pdf', 'jpg', 'png']),
            'uploaded_by' => User::factory(),
        ];
    }
}
