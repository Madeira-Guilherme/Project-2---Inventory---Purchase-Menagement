<?php

namespace Database\Factories;

use App\Models\PurchaseOrders;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseOrders>
 */
class PurchaseOrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Suppliers::factory(),

            'order_number' => 'PO-' . $this->faker->unique()->numberBetween(10000, 99999),

            'status' => $this->faker->randomElement([
                'draft',
                'submitted',
                'received',
                'cancelled'
            ]),

            'total_amount' => $this->faker->randomFloat(2, 100, 10000),

            'ordered_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'received_at' => $this->faker->optional()->dateTimeBetween('-6 months', 'now'),

            'created_by' => User::factory(),
        ];
    }
}
