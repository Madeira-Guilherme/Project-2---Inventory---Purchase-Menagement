<?php

namespace App\Mcp\Tools;

use App\Models\PurchaseOrders;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Cancel a purchase order')]
class CancelPurchaseOrder extends Tool
{
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $purchase = PurchaseOrders::find($id);

        if (!$purchase) {
            return Response::json(['message' => 'Purchase order not found'], 404);
        }

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
}
