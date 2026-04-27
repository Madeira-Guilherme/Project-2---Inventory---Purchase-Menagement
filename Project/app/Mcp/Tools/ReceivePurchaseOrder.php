<?php

namespace App\Mcp\Tools;

use App\Models\PurchaseOrders;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Illuminate\Support\Facades\DB;

#[Description('Mark a purchase order as received and update stock')]
class ReceivePurchaseOrder extends Tool
{
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $purchase = PurchaseOrders::with('items.product')->find($id);

        if (!$purchase) {
            return Response::json(['message' => 'Purchase order not found'], 404);
        }

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
}
