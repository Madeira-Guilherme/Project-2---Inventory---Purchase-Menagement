<?php

namespace App\Mcp\Tools;

use App\Models\PurchaseOrders;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Submit a draft purchase order')]
class SubmitPurchaseOrder extends Tool
{
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $purchase = PurchaseOrders::find($id);

        if (!$purchase) {
            return Response::json(['message' => 'Purchase order not found'], 404);
        }

        if ($purchase->status !== 'draft') {
            return Response::json([
                'message' => 'Only draft orders can be submitted'
            ], 400);
        }

        $purchase->update([
            'status' => 'submitted',
        ]);

        return Response::json([
            'message' => 'Purchase order submitted successfully',
            'data' => $purchase->fresh(),
        ]);
    }
}
