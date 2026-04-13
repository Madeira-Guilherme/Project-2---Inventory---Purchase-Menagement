<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrders;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Purchase Orders",
    description: "Purchase order management endpoints"
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
        return response()->json(PurchaseOrders::all());
    }

    #[OA\Post(
        path: "/api/purchaseorders",
        tags: ["Purchase Orders"],
        summary: "Create purchase order",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["supplier_id", "order_number", "total_amount", "ordered_at", "created_by"],
                properties: [
                    new OA\Property(property: "supplier_id", type: "integer"),
                    new OA\Property(property: "order_number", type: "string"),
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "total_amount", type: "number"),
                    new OA\Property(property: "ordered_at", type: "string", format: "date"),
                    new OA\Property(property: "received_at", type: "string", format: "date"),
                    new OA\Property(property: "created_by", type: "integer"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Purchase order created")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'   => 'required|integer|exists:suppliers,id',
            'order_number'  => 'required|string|unique:purchase_orders,order_number',
            'status'        => 'nullable|string',
            'total_amount'  => 'required|numeric|min:0',
            'ordered_at'    => 'required|date',
            'received_at'   => 'nullable|date',
            'created_by'    => 'required|integer|exists:users,id',
        ]);

        $purchase = PurchaseOrders::create($validated);

        return response()->json([
            'message' => 'Purchase order created successfully',
            'data' => $purchase
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
    return PurchaseOrders::findOrFail($purchases);
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
}
