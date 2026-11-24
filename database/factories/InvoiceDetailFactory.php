<?php

namespace Database\Factories;

use App\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceDetailFactory extends Factory
{
    protected $model = InvoiceDetail::class;

    public function definition(): array
    {
        $harga = fake()->randomElement([150000, 250000, 500000]);

        return [
            'qty' => 1,
            'harga' => $harga,
            'subtotal' => $harga,
        ];
    }
}
