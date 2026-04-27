<?php

namespace App\Mcp\Tools;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Illuminate\Support\Facades\Validator;
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
        $id = $request->get('purchases');

        $purchase = PurchaseOrders::find($id);

        if (!$purchase) {
            return Response::json([
                'message' => 'Purchase order not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'supplier_id'   => 'sometimes|required|integer|exists:suppliers,id',
            'order_number'  => 'sometimes|required|string|unique:purchase_orders,order_number,' . $id,
            'status'        => 'sometimes|nullable|string',
            'total_amount'  => 'sometimes|required|numeric|min:0',
            'ordered_at'    => 'sometimes|required|date',
            'received_at'   => 'sometimes|nullable|date',
            'created_by'    => 'sometimes|required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $purchase->update($validated);

        return Response::json([
            'message' => 'Purchase order updated successfully',
            'data' => (new PurchaseOrderResource($purchase->fresh()))->resolve(),
        ]);
    }
}
