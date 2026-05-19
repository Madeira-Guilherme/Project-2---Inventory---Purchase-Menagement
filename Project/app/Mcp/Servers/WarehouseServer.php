<?php

namespace App\Mcp\Servers;

use App\Mcp\Prompts\PurchaseOrderReport;
use App\Mcp\Resources\GetProducts;
use App\Mcp\Resources\GetSingleProduct;
use App\Mcp\Resources\GetPurchaseOrder;
use App\Mcp\Resources\GetRestockRequest;
use App\Mcp\Resources\GetSellOrders;
use App\Mcp\Resources\GetSinglePurchaseOrder;
use App\Mcp\Resources\GetSingleSupplier;
use App\Mcp\Resources\GetSuppliers;
use App\Mcp\Tools\AddProductToOrder;
use App\Mcp\Tools\CancelPurchaseOrder;
use App\Mcp\Tools\CancelSellOrder;
use App\Mcp\Tools\CompleteRestockRequest;
use App\Mcp\Tools\CreateProduct;
use App\Mcp\Tools\CreatePurchaseOrder;
use App\Mcp\Tools\CreateRestockRequest;
use App\Mcp\Tools\CreateSellOrder;
use App\Mcp\Tools\CreateSupplier;
use App\Mcp\Tools\DeleteProduct;
use App\Mcp\Tools\DeletePurchaseOrder;
use App\Mcp\Tools\DeleteRestockRequest;
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
use App\Models\RestockRequest;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('WarehouseServer')]
#[Version('0.0.1')]
#[Instructions('The Mcp server with the capability to interact with the entire inventory system')]
class WarehouseServer extends Server
{
    protected array $tools = [
        //Products
        CreateProduct::class,
        UpdateProduct::class,
        DeleteProduct::class,
        GetSpecificProduct::class,
        GetLowStockProducts::class,
        //Purchase Orders
        CreatePurchaseOrder::class,
        UpdatePurchaseOrder::class,
        DeletePurchaseOrder::class,
        SubmitPurchaseOrder::class,
        ReceivePurchaseOrder::class,
        CancelPurchaseOrder::class,
        GetSpecificPurchaseOrder::class,
        GetFilteredPurchaseOrders::class,
        AddProductToOrder::class,
        //Suppliers
        CreateSupplier::class,
        UpdateSupplier::class,
        DeleteSupplier::class,
        GetSpecificSupplier::class,
        //Sell Orders
        CreateSellOrder::class,
        DeleteSellOrder::class,
        UpdateSellOrder::class,
        UpdateSellOrderStatus::class,
        CancelSellOrder::class,
        GetSpecificSellOrder::class,
        //Restock Requests
        CreateRestockRequest::class,
        DeleteRestockRequest::class,
        CompleteRestockRequest::class,
    ];

    protected array $resources = [
        //Products
        GetProducts::class,
        //Purchase Orders
        GetPurchaseOrder::class,
        //Suppliers
        GetSuppliers::class,
        //Sell Orders
        GetSellOrders::class,
        //Restock Requests
        GetRestockRequest::class,
    ];

    protected array $prompts = [
        //PurchaseOrderReport::class,
    ];
}
