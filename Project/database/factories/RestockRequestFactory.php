<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use App\Models\Products;
use App\Models\RestockRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RestockRequest>
 */
class RestockRequestFactory extends Factory
{
    protected $model = RestockRequest::class;

    public function definition(): array
    {
        return [
            'requester_id' => User::factory(),
            'product_id' => Products::factory(),
            'reason' => $this->faker->sentence(),
        ];
    }
}
