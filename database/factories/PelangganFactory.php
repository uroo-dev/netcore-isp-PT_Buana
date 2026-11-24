<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'telepon' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'tipe' => fake()->randomElement(['individu', 'instansi', 'mitra']),
            'status' => fake()->randomElement(['on', 'off']),
            'tanggal_off' => null,
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn () => ['status' => 'on']);
    }

    public function nonaktif(): static
    {
        return $this->state(fn () => ['status' => 'off', 'tanggal_off' => now()->subDays(rand(1, 90))->format('Y-m-d')]);
    }

    public function individu(): static
    {
        return $this->state(fn () => ['tipe' => 'individu']);
    }

    public function instansi(): static
    {
        return $this->state(fn () => ['tipe' => 'instansi']);
    }

    public function mitra(): static
    {
        return $this->state(fn () => ['tipe' => 'mitra']);
    }
}
