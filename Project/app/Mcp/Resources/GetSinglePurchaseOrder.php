<?php

namespace App\Mcp\Resources;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use League\Uri\UriTemplate;

#[Description('Get a single purchase order')]
class GetSinglePurchaseOrder extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('purchases');

        $order = PurchaseOrders::with(['items.product'])
            ->findOrFail($id);

        return Response::json(
            (new PurchaseOrderResource($order))->resolve()
        );
    }

    /**
     * Define the URI template.
     */
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('/api/purchaseorders/{purchases}');
    }
}
