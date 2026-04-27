<?php

namespace App\Mcp\Resources;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use League\Uri\UriTemplate;

#[Description('Get all suppliers')]
class GetPurchaseOrder extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $purchaseorders = PurchaseOrders::query()->get();

        return Response::json(
            PurchaseOrderResource::collection($purchaseorders)->resolve()
        );
    }

    /**
     * Define the URI template.
     */
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('/api/suppliers');
    }
}
