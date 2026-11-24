<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $total = fake()->randomElement([150000, 250000, 500000]);

        return [
            'diskon' => 0,
            'pajak' => 0,
            'total' => $total,
            'dibayar' => 0,
            'sisa' => $total,
            'status' => 'cicilan',
            'sumber' => 'wifi',
            'metode_pembayaran' => fake()->randomElement(['cash', 'bank']),
        ];
    }
}
