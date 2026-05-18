<?php

namespace App\Mcp\Servers;

use App\Mcp\Prompts\PurchaseOrderReport;
use App\Mcp\Resources\GetProducts;
use App\Mcp\Resources\GetSingleProduct;
use App\Mcp\Resources\GetPurchaseOrder;
use App\Mcp\Resources\GetSellOrders;
use App\Mcp\Resources\GetSinglePurchaseOrder;
use App\Mcp\Resources\GetSingleSupplier;
use App\Mcp\Resources\GetSuppliers;
use App\Mcp\Tools\AddProductToOrder;
use App\Mcp\Tools\CancelPurchaseOrder;
use App\Mcp\Tools\CancelSellOrder;
use App\Mcp\Tools\CreateProduct;
use App\Mcp\Tools\CreatePurchaseOrder;
use App\Mcp\Tools\CreateSellOrder;
use App\Mcp\Tools\CreateSupplier;
use App\Mcp\Tools\DeleteProduct;
use App\Mcp\Tools\DeletePurchaseOrder;
use App\Mcp\Tools\DeleteSellOrder;
use App\Mcp\Tools\DeleteSupplier;
use App\Mcp\Tools\GetFilteredPurchaseOrders;
use App\Mcp\Tools\GetLowStockProducts;
use App\Mcp\Tools\GetSpecificProduct;
use App\Mcp\Tools\GetSpecificPurchaseOrder;
use App\Mcp\Tools\GetSpecificSellOrder;
use App\Mcp\Tools\GetSpecificSupplier;
use App\Mcp\Tools\ReceivePurchaseOrder;
use App\Mcp\Tools\SubmitPurchaseOrder;
use App\Mcp\Tools\UpdateProduct;
use App\Mcp\Tools\UpdatePurchaseOrder;
use App\Mcp\Tools\UpdateSellOrder;
use App\Mcp\Tools\UpdateSellOrderStatus;
use App\Mcp\Tools\UpdateSupplier;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('SellOrderServer')]
#[Version('0.0.1')]
#[Instructions('The Mcp server to interact with the SellOrders of the inventory system')]
class SellOrderServer extends Server
{
    protected array $tools = [
        DeleteSellOrder::class,
        UpdateSellOrder::class,
        GetSpecificSellOrder::class,
        UpdateSellOrderStatus::class,
        CancelSellOrder::class,
        CreateSellOrder::class,
    ];

    protected array $resources = [
        GetSellOrders::class,
    ];

    protected array $prompts = [
    ];
}
