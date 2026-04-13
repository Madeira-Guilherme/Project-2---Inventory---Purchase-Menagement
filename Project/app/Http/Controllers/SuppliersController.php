<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Suppliers",
    description: "Suppliers"
)]
class SuppliersController extends Controller
{
    #[OA\Get(
        path: "/api/suppliers",
        tags: ["Suppliers"],
        summary: "Get all suppliers",
        responses: [
            new OA\Response(
                response: 200,
                description: "List of suppliers",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "company_name", type: "string"),
                            new OA\Property(property: "contact_name", type: "string"),
                            new OA\Property(property: "email", type: "string"),
                            new OA\Property(property: "phone", type: "string"),
                            new OA\Property(property: "address", type: "string"),
                            new OA\Property(property: "is_active", type: "boolean"),
                        ]
                    )
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(Suppliers::all());
    }

    #[OA\Post(
        path: "/api/suppliers",
        tags: ["Suppliers"],
        summary: "Create a supplier",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["company_name"],
                properties: [
                    new OA\Property(property: "company_name", type: "string"),
                    new OA\Property(property: "contact_name", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "phone", type: "string"),
                    new OA\Property(property: "address", type: "string"),
                    new OA\Property(property: "is_active", type: "boolean"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Supplier created")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'is_active'    => 'boolean',
        ]);

        $supplier = Suppliers::create($validated);

        return response()->json([
            'message' => 'Supplier created successfully',
            'data' => $supplier
        ], 201);
    }

#[OA\Get(
    path: "/api/suppliers/{supplier}",
    tags: ["Suppliers"],
    summary: "Get single supplier",
    parameters: [
        new OA\Parameter(
            name: "supplier",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Supplier found",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "company_name", type: "string"),
                    new OA\Property(property: "contact_name", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "phone", type: "string"),
                    new OA\Property(property: "address", type: "string"),
                    new OA\Property(property: "is_active", type: "boolean"),
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: "Supplier not found"
        )
    ]
)]
public function show(string $supplier)
{
    return Suppliers::findOrFail($supplier);
}

    #[OA\Put(
        path: "/api/suppliers/{supplier}",
        tags: ["Suppliers"],
        summary: "Update supplier",
        parameters: [
            new OA\Parameter(
                name: "supplier",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "company_name", type: "string"),
                    new OA\Property(property: "contact_name", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "phone", type: "string"),
                    new OA\Property(property: "address", type: "string"),
                    new OA\Property(property: "is_active", type: "boolean"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Supplier updated"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function update(Request $request, string $supplier)
    {
        $supplier = Suppliers::findOrFail($supplier);

        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'is_active'    => 'boolean',
        ]);

        $supplier->update($validated);

        return response()->json([
            'message' => 'Supplier updated successfully',
            'data' => $supplier
        ]);
    }

    #[OA\Delete(
        path: "/api/suppliers/{supplier}",
        tags: ["Suppliers"],
        summary: "Delete supplier",
        parameters: [
            new OA\Parameter(
                name: "supplier",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Supplier deleted"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function destroy(string $supplier)
    {
        $supplier = Suppliers::findOrFail($supplier);
        $supplier->delete();

        return response()->json([
            'message' => 'Supplier deleted successfully'
        ]);
    }
}
