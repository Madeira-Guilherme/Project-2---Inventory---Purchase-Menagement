<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrders;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\DB;

#[OA\Tag(
    name: "Purchase Orders",
    description: "Purchase Orders"
)]
class PurchaseOrdersController extends Controller
{
    #[OA\Get(
        path: "/api/purchaseorders",
        tags: ["Purchase Orders"],
        summary: "Get all purchase orders",
        responses: [
            new OA\Response(
                response: 200,
                description: "List of purchase orders",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "supplier_id", type: "integer"),
                            new OA\Property(property: "order_number", type: "string"),
                            new OA\Property(property: "status", type: "string"),
                            new OA\Property(property: "total_amount", type: "number"),
                            new OA\Property(property: "ordered_at", type: "string"),
                            new OA\Property(property: "received_at", type: "string", nullable: true),
                            new OA\Property(property: "created_by", type: "integer"),
                        ]
                    )
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(
            PurchaseOrders::with('items.product')->get()
        );
    }

    #[OA\Post(
    path: "/api/purchaseorders",
    tags: ["Purchase Orders"],
    summary: "Create purchase order with items",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: [
                "supplier_id",
                "order_number",
                "total_amount",
                "ordered_at",
                "created_by",
                "items"
            ],
            properties: [
                new OA\Property(property: "supplier_id", type: "integer", example: 1),
                new OA\Property(property: "order_number", type: "string", example: "PO-001"),
                new OA\Property(property: "total_amount", type: "number", example: 100),
                new OA\Property(property: "ordered_at", type: "string", format: "date", example: "2026-04-14"),
                new OA\Property(property: "received_at", type: "string", format: "date", nullable: true),
                new OA\Property(property: "created_by", type: "integer", example: 1),

                // 🔥 THIS is the important part
                new OA\Property(
                    property: "items",
                    type: "array",
                    description: "List of products in this purchase order",
                    items: new OA\Items(
                        type: "object",
                        required: ["product_id", "quantity"],
                        properties: [
                            new OA\Property(
                                property: "product_id",
                                type: "integer",
                                example: 1
                            ),
                            new OA\Property(
                                property: "quantity",
                                type: "integer",
                                example: 2
                            ),
                        ]
                    )
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: "Purchase order created successfully"
        ),
        new OA\Response(
            response: 422,
            description: "Validation error"
        )
    ]
)]
public function store(Request $request)
{
    $validated = $request->validate([
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

    return response()->json([
        'message' => 'Purchase order created successfully',
        'data' => $purchase->load('items.product')
    ], 201);
}


    #[OA\Get(
        path: "/api/purchaseorders/{purchases}",
        tags: ["Purchase Orders"],
        summary: "Get single purchase order",
        parameters: [
            new OA\Parameter(
                name: "purchases",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Purchase order found"
            ),
            new OA\Response(
                response: 404,
                description: "Not found"
            )
        ]
    )]
    public function show(string $purchases)
    {
        return PurchaseOrders::with('items.product')
            ->findOrFail($purchases);
    }

    #[OA\Put(
        path: "/api/purchaseorders/{purchases}",
        tags: ["Purchase Orders"],
        summary: "Update purchase order",
        parameters: [
            new OA\Parameter(
                name: "purchases",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "supplier_id", type: "integer"),
                    new OA\Property(property: "order_number", type: "string"),
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "total_amount", type: "number"),
                    new OA\Property(property: "ordered_at", type: "string"),
                    new OA\Property(property: "received_at", type: "string"),
                    new OA\Property(property: "created_by", type: "integer"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Updated"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function update(Request $request, string $purchases)
    {
        $purchase = PurchaseOrders::find($purchases);

        if (!$purchase) {
            return response()->json([
                'message' => 'Purchase order not found'
            ], 404);
        }

        $validated = $request->validate([
            'supplier_id'   => 'sometimes|required|integer|exists:suppliers,id',
            'order_number'  => 'sometimes|required|string|unique:purchase_orders,order_number,' . $purchases,
            'status'        => 'sometimes|nullable|string',
            'total_amount'  => 'sometimes|required|numeric|min:0',
            'ordered_at'    => 'sometimes|required|date',
            'received_at'   => 'sometimes|nullable|date',
            'created_by'    => 'sometimes|required|integer|exists:users,id',
        ]);

        $purchase->update($validated);

        return response()->json([
            'message' => 'Purchase order updated successfully',
            'data' => $purchase
        ]);
    }

    #[OA\Delete(
        path: "/api/purchaseorders/{purchases}",
        tags: ["Purchase Orders"],
        summary: "Delete purchase order",
        parameters: [
            new OA\Parameter(
                name: "purchases",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Deleted"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function destroy(string $purchases)
    {
        $purchase = PurchaseOrders::find($purchases);

        if (!$purchase) {
            return response()->json([
                'message' => 'Purchase order not found'
            ], 404);
        }

        $purchase->delete();

        return response()->json([
            'message' => 'Purchase order deleted successfully'
        ]);
    }

    #[OA\Post(
        path: "/api/purchaseorders/{id}/submit",
        tags: ["Purchase Orders"],
        summary: "Submit purchase order",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Submitted successfully"),
            new OA\Response(response: 400, description: "Invalid status transition"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function submit(string $id)
    {
        $purchase = PurchaseOrders::find($id);

        if (!$purchase) {
            return response()->json(['message' => 'Purchase order not found'], 404);
        }

        if ($purchase->status !== "draft") {
            return response()->json([
                'message' => 'Only draft orders can be submitted'
            ], 400);
        }

        $purchase->load('items.product');

        foreach ($purchase->items as $item) {

            $product = $item->product;

                if ($product) {

                        if ($product->stock_quantity < $item->quantity) {
                            throw new \Exception("Not enough stock for product ID {$product->id}");
                        }

                        $product->decrement('stock_quantity', $item->quantity);
                    }
                }
        $purchase->update([
            'status' => "submitted"
        ]);

        return response()->json([
            'message' => 'Purchase order submitted successfully',
            'data' => $purchase
        ]);
    }

    #[OA\Post(
    path: "/api/purchaseorders/{id}/receive",
    tags: ["Purchase Orders"],
    summary: "Mark purchase order as received",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Received successfully"),
        new OA\Response(response: 400, description: "Invalid status transition"),
        new OA\Response(response: 404, description: "Not found")
    ]
    )]
    public function receive(string $id)
    {
        $purchase = PurchaseOrders::find($id);

        if (!$purchase) {
            return response()->json(['message' => 'Purchase order not found'], 404);
        }

        if ($purchase->status !== "submitted") {
            return response()->json([
                'message' => 'Only submitted orders can be received'
            ], 400);
        }

        $purchase->update([
            'status' => "received",
            'received_at' => now()
        ]);

        return response()->json([
            'message' => 'Purchase order received successfully',
            'data' => $purchase
        ]);
    }
    #[OA\Post(
    path: "/api/purchaseorders/{id}/cancel",
    tags: ["Purchase Orders"],
    summary: "Cancel purchase order",
    parameters: [
        new OA\Parameter(
            name: "id",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Cancelled successfully"),
        new OA\Response(response: 400, description: "Invalid status transition"),
        new OA\Response(response: 404, description: "Not found")
    ]
    )]
    public function cancel(string $id)
    {
        $purchase = PurchaseOrders::find($id);

        if (!$purchase) {
            return response()->json(['message' => 'Purchase order not found'], 404);
        }

        if (in_array($purchase->status, [
            "recieved",
            "cancelled"
        ])) {
            return response()->json([
                'message' => 'This order cannot be cancelled'
            ], 400);
        }

        $purchase->update([
            'status' => "cancelled",
        ]);

        return response()->json([
            'message' => 'Purchase order cancelled successfully',
            'data' => $purchase
        ]);
    }
}
