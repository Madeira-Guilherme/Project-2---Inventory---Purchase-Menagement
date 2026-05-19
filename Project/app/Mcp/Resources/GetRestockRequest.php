<?php

namespace App\Mcp\Resources;

use App\Http\Resources\PurchaseOrderResource;
use App\Http\Resources\SellOrderResource;
use App\Models\PurchaseOrders;
use App\Models\RestockRequest;
use App\Models\SellOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use League\Uri\UriTemplate;

#[Description('Get all Sell Orders')]
class GetRestockRequest extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $orders = RestockRequest::query()->get();

        return Response::json(
            SellOrderResource::collection($orders)->resolve()
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
