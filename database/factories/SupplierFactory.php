<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'document' => preg_replace('/\D/', '', $this->faker->numerify('##############')),
            'email' => $this->faker->companyEmail,
            'phone' => preg_replace('/\D/', '', $this->faker->phoneNumber),
        ];
    }
}
