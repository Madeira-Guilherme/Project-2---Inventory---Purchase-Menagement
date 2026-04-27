<?php

namespace App\Mcp\Tools;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return Response::json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

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
}
