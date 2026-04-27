<?php

namespace App\Mcp\Tools;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get a single purchase order')]
class GetSpecificPurchaseOrder extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $order = PurchaseOrders::with(['items.product'])
            ->findOrFail($id);

        return Response::json(
            (new PurchaseOrderResource($order))->resolve()
        );
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the purchase order to retrieve.')
                ->required(),
        ];
    }
}
