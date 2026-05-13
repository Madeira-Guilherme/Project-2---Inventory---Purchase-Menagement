<?php

use App\Mcp\Servers\ProductsServer;
use App\Mcp\Servers\PurchaseOrderServer;
use App\Mcp\Servers\SellOrderServer;
use App\Mcp\Servers\SupplierServer;
use App\Mcp\Servers\WarehouseServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/demo', WarehouseServer::class)
    ->middleware(['auth:sanctum']);

Mcp::web('/mcp/suppliers', SupplierServer::class)
    ->middleware(['auth:sanctum']);

Mcp::web('/mcp/products', ProductsServer::class)
    ->middleware(['auth:sanctum']);

Mcp::web('/mcp/purchaseorders', PurchaseOrderServer::class)
    ->middleware(['auth:sanctum']);

Mcp::web('/mcp/sellorders', SellOrderServer::class)
    ->middleware(['auth:sanctum']);
