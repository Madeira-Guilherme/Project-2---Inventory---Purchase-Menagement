<?php

namespace App\Mcp\Tools;

use App\Http\Resources\PurchaseOrderResource;
use App\Http\Resources\SellOrderResource;
use App\Models\Products;
use App\Models\PurchaseOrders;
use App\Models\SellOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a sell order with items')]
class CreateSellOrder extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'purchaser_id' => 'required|integer|exists:suppliers,id',
            'ordered_at'  => 'required|date',
            'received_at' => 'nullable|date',
            'created_by'  => 'required|integer|exists:users,id',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $purchase = DB::transaction(function () use ($validated) {

            // safer order number (no race condition)
            $orderNumber = 'PO-' . now()->format('Ymd') . '-' . SellOrders::max('id') + 1;

            $purchase = SellOrders::create([
                'purchaser_id' => $validated['purchaser_id'],
                'order_number' => $orderNumber,
                'status' => 'draft',
                'total_amount' => 0,
                'ordered_at' => $validated['ordered_at'],
                'received_at' => $validated['received_at'] ?? null,
                'created_by' => $validated['created_by'],
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Products::findOrFail($item['product_id']);

                $lineTotal = $product->unit_price * $item['quantity'];
                $totalAmount += $lineTotal;

                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->unit_price,
                ]);
            }

            $purchase->update([
                'total_amount' => $totalAmount,
            ]);

            return $purchase;
        });

        return Response::json([
            'message' => 'Purchase order created successfully',
            'data' => (new SellOrderResource(
                $purchase->load('items.product')
            ))->resolve(),
        ], 201);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'purchaser_id' => $schema->integer()
                ->description('ID of the purchaser.')
                ->required(),

            'ordered_at' => $schema->string()
                ->description('Order date (ISO format).')
                ->required(),

            'received_at' => $schema->string()
                ->description('Received date (ISO format).')
                ->nullable(),

            'created_by' => $schema->integer()
                ->description('User ID who created the order.')
                ->required(),

            'items' => $schema->array(
                $schema->object([
                    'product_id' => $schema->integer()
                        ->description('Product ID.')
                        ->required(),

                    'quantity' => $schema->integer()
                        ->description('Quantity ordered.')
                        ->required(),
                ])
            )->description('List of purchase order items.')
             ->required(),
        ];
    }
}
