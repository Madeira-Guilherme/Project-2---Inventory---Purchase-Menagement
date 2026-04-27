<?php

namespace App\Mcp\Servers;

use App\Mcp\Resources\GetProducts;
use App\Mcp\Resources\GetSingleProduct;
use App\Mcp\Resources\GetPurchaseOrder;
use App\Mcp\Resources\GetSinglePurchaseOrder;
use App\Mcp\Tools\CancelPurchaseOrder;
use App\Mcp\Tools\CreateProduct;
use App\Mcp\Tools\CreatePurchaseOrder;
use App\Mcp\Tools\DeleteProduct;
use App\Mcp\Tools\DeletePurchaseOrder;
use App\Mcp\Tools\ReceivePurchaseOrder;
use App\Mcp\Tools\SubmitPurchaseOrder;
use App\Mcp\Tools\UpdateProduct;
use App\Mcp\Tools\UpdatePurchaseOrder;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Warehouse Server')]
#[Version('0.0.1')]
#[Instructions('Instructions describing how to use the server and its features.')]
class WarehouseServer extends Server
{
    protected array $tools = [
        //Products
        CreateProduct::class,
        UpdateProduct::class,
        DeleteProduct::class,
        //Purchase Orders
        CreatePurchaseOrder::class,
        UpdatePurchaseOrder::class,
        DeletePurchaseOrder::class,
        SubmitPurchaseOrder::class,
        ReceivePurchaseOrder::class,
        CancelPurchaseOrder::class,
        //Suppliers
    ];

    protected array $resources = [
        //Products
        GetSingleProduct::class,
        GetProducts::class,
        //Purchase Orders
        GetPurchaseOrder::class,
        GetSinglePurchaseOrder::class,
        //Suppliers
    ];

    protected array $prompts = [
        //
    ];
}
