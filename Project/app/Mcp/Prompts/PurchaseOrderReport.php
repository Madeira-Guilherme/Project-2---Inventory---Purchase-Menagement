<?php
namespace App\Mcp\Prompts;

use App\Models\PurchaseOrders;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;

#[Description('Generates a report on purchase orders within a date range.')]
class PurchaseOrderReport extends Prompt
{
    /**
     * Handle the prompt request.
     */
    public function handle(Request $request): Response
    {
        $purchaseOrders = PurchaseOrders::query()->get();

        return Response::text("
            Generate a purchase order report.
            Total Orders: {$purchaseOrders->count()}
        ");
    }
}
