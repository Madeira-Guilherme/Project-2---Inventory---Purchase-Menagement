<?php

use App\Mcp\Servers\WarehouseServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/demo', WarehouseServer::class)
    ->middleware(['auth:sanctum']);
