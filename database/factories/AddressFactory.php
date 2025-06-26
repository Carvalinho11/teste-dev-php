<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'zipcode' => '12345-678',
            'street' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'complement' => 'Esquina com a ' . $this->faker->numberBetween(1, 99),
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];
    }
}
