<?php

namespace App\Mcp\Tools;

use App\Http\Resources\SellOrderResource;
use App\Models\Products;
use App\Models\SellOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Cancel a sell order and restore stock if needed')]
class CancelSellOrder extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'sell_order_id' => 'required|integer|exists:sell_orders,id',
        ]);

        $sellOrder = DB::transaction(function () use ($validated) {

            $sellOrder = SellOrders::with('items.product')->findOrFail(
                $validated['sell_order_id']
            );

            /**
             * Prevent cancelling twice
             */
            if ($sellOrder->status === 'cancelled') {
                abort(422, 'Sell order is already cancelled.');
            }

            /**
             * If stock was already deducted
             * (submitted or received),
             * restore it.
             */
            if (
                in_array($sellOrder->status, [
                    'submitted',
                    'received',
                ])
            ) {
                foreach ($sellOrder->items as $item) {

                    $product = Products::lockForUpdate()->findOrFail(
                        $item->product_id
                    );

                    $product->increment(
                        'stock_quantity',
                        $item->quantity
                    );
                }
            }

            /**
             * Update status
             */
            $sellOrder->update([
                'status' => 'cancelled',
            ]);

            return $sellOrder->fresh()->load('items.product');
        });

        return Response::json([
            'message' => 'Sell order cancelled successfully',
            'data' => (new SellOrderResource($sellOrder))->resolve(),
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'sell_order_id' => $schema->integer()
                ->description('ID of the sell order.')
                ->required(),
        ];
    }
}
