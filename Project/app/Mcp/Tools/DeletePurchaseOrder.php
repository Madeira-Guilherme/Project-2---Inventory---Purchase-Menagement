<?php

namespace App\Mcp\Tools;

use App\Models\PurchaseOrders;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Delete a purchase order')]
class DeletePurchaseOrder extends Tool
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

        $purchase->delete();

        return Response::json([
            'message' => 'Purchase order deleted successfully',
        ]);
    }
}
