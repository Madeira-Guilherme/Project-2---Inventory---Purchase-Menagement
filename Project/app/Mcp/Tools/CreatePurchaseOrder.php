<?php

namespace App\Mcp\Tools;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a purchase order with items')]
class CreatePurchaseOrder extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'supplier_id'   => 'required|integer|exists:suppliers,id',
            'order_number'  => 'required|string|unique:purchase_orders,order_number',
            'total_amount'  => 'required|numeric|min:0',
            'ordered_at'    => 'required|date',
            'received_at'   => 'nullable|date',
            'created_by'    => 'required|integer|exists:users,id',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $purchase = DB::transaction(function () use ($validated) {
            $purchase = PurchaseOrders::create([
                'supplier_id' => $validated['supplier_id'],
                'order_number' => $validated['order_number'],
                'status' => 'draft',
                'total_amount' => $validated['total_amount'],
                'ordered_at' => $validated['ordered_at'],
                'received_at' => $validated['received_at'] ?? null,
                'created_by' => $validated['created_by'],
            ]);

            foreach ($validated['items'] as $item) {
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $purchase;
        });

        return Response::json([
            'message' => 'Purchase order created successfully',
            'data' => (new PurchaseOrderResource(
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
            'supplier_id' => $schema->integer()
                ->description('Supplier ID.')
                ->required(),

            'order_number' => $schema->string()
                ->description('Unique purchase order number.')
                ->required(),

            'total_amount' => $schema->number()
                ->description('Total amount of the purchase order.')
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
