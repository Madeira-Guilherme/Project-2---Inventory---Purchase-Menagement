<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Products",
    description: "Manage products, including creation and updates"
)]
class ProductsController extends Controller
{

#[OA\Get(
    path: "/api/products",
    tags: ["Products"],
    summary: "Get all products",
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "max_stock",
            in: "query",
            required: false,
            description: "Filter products by max stock quantity",
            schema: new OA\Schema(type: "integer")
        ),
        new OA\Parameter(
            name: "min_stock",
            in: "query",
            required: false,
            description: "Filter products by minimum stock quantity",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "List of products",
            content: new OA\JsonContent(
                type: "array",
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "sku", type: "string"),
                        new OA\Property(property: "description", type: "string"),
                        new OA\Property(property: "unit_price", type: "number"),
                        new OA\Property(property: "stock_quantity", type: "integer"),
                        new OA\Property(property: "is_active", type: "boolean"),
                        new OA\Property(property: "deleted_at", type: "string"),
                        new OA\Property(property: "created_at", type: "string"),
                        new OA\Property(property: "updated_at", type: "string"),
                    ]
                )
            )
        )
    ]
)]
public function index(Request $request)
{
    $query = Products::query();

    if ($request->has('min_stock')) {
    $query->where('stock_quantity', '>=', $request->min_stock);
    }

    if ($request->has('max_stock')) {
        $query->where('stock_quantity', '<=', $request->max_stock);
    }

    $products = $query->get();

    return ProductsResource::collection($products);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

#[OA\Post(
    path: "/api/products",
    tags: ["Products"],
    summary: "Create product",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["name", "sku", "unit_price", "stock_quantity"],
            properties: [
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "sku", type: "string"),
                new OA\Property(property: "description", type: "string"),
                new OA\Property(property: "unit_price", type: "number"),
                new OA\Property(property: "stock_quantity", type: "integer"),
                new OA\Property(property: "is_active", type: "boolean")

            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: "Created"),
        new OA\Response(response: 401, description: "Unauthorized"),
        new OA\Response(response: 403, description: "No Permission"),
        new OA\Response(response: 422, description: "Validation error"),

    ]
)]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $product = Products::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    #[OA\Get(
    path: "/api/products/{product}",
    tags: ["Products"],
    summary: "Get single product",
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "product",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Product Found",
            content: new OA\JsonContent(
                type: "array",
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "sku", type: "string"),
                        new OA\Property(property: "description", type: "string"),
                        new OA\Property(property: "unit_price", type: "number"),
                        new OA\Property(property: "stock_quantity", type: "integer"),
                        new OA\Property(property: "is_active", type: "boolean"),
                        new OA\Property(property: "deleted_at", type: "string"),
                        new OA\Property(property: "created_at", type: "string"),
                        new OA\Property(property: "updated_at", type: "string"),
                    ]
                )
            )
        ),
        new OA\Response(response: 404, description: "Not found"),
        new OA\Response(response: 403, description: "No Permission"),
    ]
)]
    public function show(string $id)
    {
        $product = Products::findOrFail($id);

        return new ProductsResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

#[OA\Put(
    path: "/api/products/{product}",
    tags: ["Products"],
    summary: "Update product",
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "product",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "sku", type: "string"),
                new OA\Property(property: "description", type: "string"),
                new OA\Property(property: "unit_price", type: "number"),
                new OA\Property(property: "stock_quantity", type: "integer"),
                new OA\Property(property: "is_active", type: "boolean")
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Updated",
            content: new OA\JsonContent(
                type: "array",
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "sku", type: "string"),
                        new OA\Property(property: "description", type: "string"),
                        new OA\Property(property: "unit_price", type: "number"),
                        new OA\Property(property: "stock_quantity", type: "integer"),
                        new OA\Property(property: "is_active", type: "boolean"),
                        new OA\Property(property: "deleted_at", type: "string"),
                        new OA\Property(property: "created_at", type: "string"),
                        new OA\Property(property: "updated_at", type: "string"),
                    ]
                )
            )
        ),
        new OA\Response(response: 403, description: "No Permission"),
        new OA\Response(response: 404, description: "Not found")
    ]
)]
    public function update(Request $request, Products $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:255|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'unit_price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product->fresh()
        ]);
    }

#[OA\Delete(
    path: "/api/products/{product}",
    tags: ["Products"],
    summary: "Delete product",
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "product",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Deleted"),
        new OA\Response(response: 403, description: "No Permission"),
        new OA\Response(response: 404, description: "Not found")
    ]
)]
    public function destroy(Products $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    #[OA\Post(
        path: "/api/products/{id}/restore",
        tags: ["Products"],
        summary: "Restore soft deleted product",
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID of the soft deleted product",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product restored successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Product restored")
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: "No Permission"
            ),
            new OA\Response(
                response: 404,
                description: "Product not found"
            )
        ]
    )]
    public function restore($id)
    {
        $product = Products::onlyTrashed()->findOrFail($id);
        $product->restore();

        return response()->json([
            'message' => 'Product restored'
        ]);
    }

    #
}
