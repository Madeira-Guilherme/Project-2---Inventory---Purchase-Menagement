<?php

namespace App\Mcp\Tools;

use App\Models\PurchaseOrders;
use App\Models\SellOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Delete a Sell Order')]
class DeleteSellOrder extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $purchase = SellOrders::findOrFail($id);

        $purchase->delete();

        return Response::json([
            'message' => 'Sell Order deleted successfully',
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the Sell Order to delete.')
                ->required(),
        ];
    }
}
