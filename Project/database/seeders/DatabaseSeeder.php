<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Roles ------------------------------------------
        $admin = Role::create(['name' => 'admin']);
        $werehouseop = Role::create(['name' => 'warehouseoperator']);
        $purchaser = Role::create(['name' => 'purchaser']);

        Permission::create(['name' => 'create orders']);
        Permission::create(['name' => 'update orders']);
        Permission::create(['name' => 'mark orders']);
        Permission::create(['name' => 'update products']);
        Permission::create(['name' => 'create products']);

        $admin->givePermissionTo(Permission::all());
        $purchaser->givePermissionTo('create orders');
        $purchaser->givePermissionTo('update orders');
        $werehouseop->givePermissionTo('mark orders');
        $werehouseop->givePermissionTo('update products');
        $werehouseop->givePermissionTo('create products');

        // The Good Stuff -------------------------------
        Products::factory(10)->create();
        Suppliers::factory(10)->create();
        PurchaseOrders::factory(10)->create();
        PurchaseOrdersItems::factory(10)->create();
        User::factory()
        ->count(20)
        ->create()
        ->each(function ($user)
        { $user->assignRole(
             fake()->randomElement(['purchaser', 'warehouseoperator'])
            );
        });
    }
}
