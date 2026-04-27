<?php

use App\Mcp\Servers\WarehouseServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::api('/mcp/demo', WarehouseServer::class)
    ->middleware(['auth:sanctum']);
