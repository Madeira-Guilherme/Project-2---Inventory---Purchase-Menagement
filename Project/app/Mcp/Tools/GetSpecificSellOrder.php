<?php

namespace App\Mcp\Tools;

use App\Http\Resources\PurchaseOrderResource;
use App\Http\Resources\SellOrderResource;
use App\Models\PurchaseOrders;
use App\Models\SellOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get a single sell order')]
class GetSpecificSellOrder extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $order = SellOrders::with(['items.product'])
            ->findOrFail($id);

        return Response::json(
            (new SellOrderResource($order))->resolve()
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
