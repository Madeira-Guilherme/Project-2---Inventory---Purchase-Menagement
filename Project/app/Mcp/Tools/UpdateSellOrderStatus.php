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

#[Description('Advance sell order status and manage stock')]
class UpdateSellOrderStatus extends Tool
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
             * Status flow:
             * draft -> submitted -> received
             */
            $nextStatuses = [
                'draft' => 'submitted',
                'submitted' => 'received',
            ];

            $currentStatus = $sellOrder->status;

            if (! isset($nextStatuses[$currentStatus])) {
                abort(
                    422,
                    "No further status transition allowed from {$currentStatus}"
                );
            }

            $newStatus = $nextStatuses[$currentStatus];

            /**
             * When order becomes submitted:
             * decrease stock
             */
            if ($newStatus === 'submitted') {

                foreach ($sellOrder->items as $item) {

                    $product = Products::lockForUpdate()->findOrFail(
                        $item->product_id
                    );

                    if ($product->stock_quantity < $item->quantity) {
                        abort(
                            422,
                            "Insufficient stock for product: {$product->name}"
                        );
                    }

                    $product->decrement(
                        'stock_quantity',
                        $item->quantity
                    );
                }
            }

            /**
             * Update status
             */
            $sellOrder->update([
                'status' => $newStatus,
            ]);

            return $sellOrder->fresh()->load('items.product');
        });

        return Response::json([
            'message' => 'Sell order status updated successfully',
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
