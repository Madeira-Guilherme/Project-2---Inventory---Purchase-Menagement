<?php

namespace Database\Factories;

use App\Models\PurchaseOrdersItems;
use App\Models\Products;
use App\Models\PurchaseOrders;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseOrdersItems>
 */
class PurchaseOrdersItemsFactory extends Factory
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
            'purchase_order_id' => PurchaseOrders::factory(),
            'product_id' => $product->id,

            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $quantity * $unitPrice,
        ];
    }
}
