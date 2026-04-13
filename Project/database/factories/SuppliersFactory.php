<?php

namespace Database\Factories;

use App\Models\Suppliers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Suppliers>
 */
class SuppliersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
