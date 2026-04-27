<?php

namespace App\Mcp\Tools;

use App\Models\PurchaseOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Cancel a purchase order')]
class CancelPurchaseOrder extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $purchase = PurchaseOrders::findOrFail($id);

        if (in_array($purchase->status, ['received', 'cancelled'])) {
            return Response::json([
                'message' => 'This order cannot be cancelled'
            ], 400);
        }

        $purchase->update([
            'status' => 'cancelled',
        ]);

        return Response::json([
            'message' => 'Purchase order cancelled successfully',
            'data' => $purchase->fresh(),
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the purchase order to cancel.')
                ->required(),
        ];
    }
}
