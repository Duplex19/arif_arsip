<?php

namespace Database\Factories;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'bidang_id' => Bidang::factory(),
            'role' => 'user',
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => 'admin',
            'role' => 'admin',
            'bidang_id' => null,
        ]);
    }

    public function pimpinan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pimpinan',
        ]);
    }

    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'user',
        ]);
    }
}
