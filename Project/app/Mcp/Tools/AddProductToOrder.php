<?php
namespace App\Mcp\Tools;

use App\Models\Products;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class AddProductToOrder extends Tool
{
    protected string $description = 'Add a product to an existing purchase order.';

    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|integer|exists:purchase_orders,id',
            'product_id'        => 'required|integer|exists:products,id',
            'quantity'          => 'required|integer|min:1',
        ]);

        $order = PurchaseOrders::findOrFail($validated['purchase_order_id']);

        if ($order->status !== 'draft') {
            return Response::text("Cannot add items to a purchase order with status '{$order->status}'. Only draft orders can be modified.");
        }

        $existingItem = PurchaseOrdersItems::where('purchase_order_id', $order->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingItem) {
            return Response::text("Product #{$validated['product_id']} is already on this order. Use UpdateOrderItem to change the quantity.");
        }

        $product = Products::findOrFail($validated['product_id']);
        $lineTotal = $validated['quantity'] * $product->unit_price;

        PurchaseOrdersItems::create([
            'purchase_order_id' => $order->id,
            'product_id'        => $product->id,
            'quantity'          => $validated['quantity'],
            'unit_price'        => $product->unit_price,
            'line_total'        => $lineTotal,
        ]);

        $order->total_amount = PurchaseOrdersItems::where('purchase_order_id', $order->id)
            ->sum('line_total');
        $order->save();

        return Response::text("
            Added {$validated['quantity']}x {$product->name} to order #{$order->order_number}.
            Unit price: \${$product->unit_price}
            New products total: \${$order->total_amount}
        ");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'purchase_order_id' => $schema->integer()
                ->description('The ID of the purchase order to add the item to')
                ->required(),
            'product_id'        => $schema->integer()
                ->description('The ID of the product to add')
                ->required(),
            'quantity'          => $schema->integer()
                ->description('The quantity to order')
                ->required(),
        ];
    }
}
