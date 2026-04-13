<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Products::factory(10)->create();
        Suppliers::factory(10)->create();
        PurchaseOrders::factory(10)->create();
        PurchaseOrdersItems::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
