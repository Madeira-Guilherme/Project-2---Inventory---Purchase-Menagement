<?php

namespace App\Mcp\Tools;

use App\Models\PurchaseOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Submit a draft purchase order')]
class SubmitPurchaseOrder extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $purchase = PurchaseOrders::findOrFail($id);

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

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the purchase order to submit.')
                ->required(),
        ];
    }
}
