<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductsController extends Controller
{

#[OA\Get(
    path: "/api/products",
    tags: ["Products"],
    summary: "Get all products",
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
                        new OA\Property(property: "unit_price", type: "number"),
                        new OA\Property(property: "stock_quantity", type: "integer"),
                    ]
                )
            )
        )
    ]
)]
    public function index()
    {
        $products = Products::all();

        return response()->json($products);
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
        new OA\Response(response: 201, description: "Created")
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

        $validated['is_active'] = $request->boolean('is_active');

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
            description: "Product found"
        ),
        new OA\Response(
            response: 404,
            description: "Not found"
        )
    ]
)]
    public function show(string $id)
    {
        $product = Products::findOrFail($id);

        return response()->json($product);
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
        new OA\Response(response: 200, description: "Updated"),
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
}
