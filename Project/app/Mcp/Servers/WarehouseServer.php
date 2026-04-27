<?php

namespace App\Mcp\Servers;

use App\Mcp\Resources\GetProducts;
use App\Mcp\Resources\GetSingleProduct;
use App\Mcp\Resources\GetPurchaseOrder;
use App\Mcp\Resources\GetSinglePurchaseOrder;
use App\Mcp\Resources\GetSingleSupplier;
use App\Mcp\Resources\GetSuppliers;
use App\Mcp\Tools\CancelPurchaseOrder;
use App\Mcp\Tools\CreateProduct;
use App\Mcp\Tools\CreatePurchaseOrder;
use App\Mcp\Tools\CreateSupplier;
use App\Mcp\Tools\DeleteProduct;
use App\Mcp\Tools\DeletePurchaseOrder;
use App\Mcp\Tools\DeleteSupplier;
use App\Mcp\Tools\GetFilteredPurchaseOrders;
use App\Mcp\Tools\GetSpecificProduct;
use App\Mcp\Tools\GetSpecificPurchaseOrder;
use App\Mcp\Tools\GetSpecificSupplier;
use App\Mcp\Tools\ReceivePurchaseOrder;
use App\Mcp\Tools\SubmitPurchaseOrder;
use App\Mcp\Tools\UpdateProduct;
use App\Mcp\Tools\UpdatePurchaseOrder;
use App\Mcp\Tools\UpdateSupplier;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('WarehouseServer')]
#[Version('0.0.1')]
#[Instructions('Instructions describing how to use the server and its features.')]
class WarehouseServer extends Server
{
    protected array $tools = [
        //Products
        CreateProduct::class,
        UpdateProduct::class,
        DeleteProduct::class,
        GetSpecificProduct::class,
        //Purchase Orders
        CreatePurchaseOrder::class,
        UpdatePurchaseOrder::class,
        DeletePurchaseOrder::class,
        SubmitPurchaseOrder::class,
        ReceivePurchaseOrder::class,
        CancelPurchaseOrder::class,
        GetSpecificPurchaseOrder::class,
        GetFilteredPurchaseOrders::class,
        //Suppliers
        CreateSupplier::class,
        UpdateSupplier::class,
        DeleteSupplier::class,
        GetSpecificSupplier::class,
    ];

    protected array $resources = [
        //Products
        GetProducts::class,
        //Purchase Orders
        GetPurchaseOrder::class,
        //Suppliers
        GetSuppliers::class,
    ];

    protected array $prompts = [
        //
    ];
}
