<?php

namespace App\Mcp\Tools;

use App\Models\PurchaseOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Mark a purchase order as received and update stock')]
class ReceivePurchaseOrder extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $purchase = PurchaseOrders::with('items.product')->findOrFail($id);

        if ($purchase->status !== 'submitted') {
            return Response::json([
                'message' => 'Only submitted orders can be received'
            ], 400);
        }

        DB::transaction(function () use ($purchase) {

            foreach ($purchase->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            $purchase->update([
                'status' => 'received',
                'received_at' => now(),
            ]);
        });

        return Response::json([
            'message' => 'Purchase order received successfully',
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
                ->description('ID of the purchase order to receive.')
                ->required(),
        ];
    }
}
