<?php

namespace App\Mcp\Tools;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Update a purchase order')]
class UpdatePurchaseOrder extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $purchase = PurchaseOrders::find($id);

        if (!$purchase) {
            return Response::json([
                'message' => 'Purchase order not found'
            ], 404);
        }

        $validated = $request->validate([
            'supplier_id'   => 'sometimes|required|integer|exists:suppliers,id',
            'order_number'  => 'sometimes|required|string|unique:purchase_orders,order_number,' . $id,
            'status'        => 'sometimes|nullable|string',
            'total_amount'  => 'sometimes|required|numeric|min:0',
            'ordered_at'    => 'sometimes|required|date',
            'received_at'   => 'sometimes|nullable|date',
            'created_by'    => 'sometimes|required|integer|exists:users,id',
        ]);

        $purchase->update($validated);

        return Response::json([
            'message' => 'Purchase order updated successfully',
            'data' => (new PurchaseOrderResource($purchase->fresh()))->resolve(),
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the purchase order to update.')
                ->required(),

            'supplier_id' => $schema->integer()
                ->description('Supplier ID.')
                ->nullable(),

            'order_number' => $schema->string()
                ->description('Unique purchase order number.')
                ->nullable(),

            'status' => $schema->string()
                ->description('Status of the purchase order.')
                ->nullable(),

            'total_amount' => $schema->number()
                ->description('Total amount of the purchase order.')
                ->nullable(),

            'ordered_at' => $schema->string()
                ->description('Order date (ISO format).')
                ->nullable(),

            'received_at' => $schema->string()
                ->description('Received date (ISO format).')
                ->nullable(),

            'created_by' => $schema->integer()
                ->description('User ID who created the order.')
                ->nullable(),
        ];
    }
}
