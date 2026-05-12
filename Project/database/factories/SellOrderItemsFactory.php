<?php

namespace Database\Factories;

use App\Models\SellOrderItems;
use App\Models\Products;
use App\Models\PurchaseOrders;
use App\Models\SellOrders;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SellOrderItems>
 */
class SellOrderItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $product = Products::factory()->create();
        $quantity = $this->faker->numberBetween(1, 50);
        $unitPrice = $product->unit_price;

        return [
            'sell_order_id' => SellOrders::factory(),
            'product_id' => $product->id,

            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $quantity * $unitPrice,
        ];
    }
}
