<?php

namespace Database\Factories;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidangFactory extends Factory
{
    protected $model = Bidang::class;

    public function definition(): array
    {
        static $counter = 0;
        $counter++;

        return [
            'nama_bidang' => 'Bidang '.fake()->unique()->word().' '.$counter,
        ];
    }
}
