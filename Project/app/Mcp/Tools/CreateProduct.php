<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new product for the werehouse server.')]
class CreateProduct extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Example: pretend we created a product
        $product = [
            'id' => 1,
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'description' => $validated['description'] ?? null,
            'unit_price' => $validated['unit_price'],
            'stock_quantity' => $validated['stock_quantity'],
            'is_active' => $validated['is_active'] ?? true,
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ];

        return Response::json($product);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('Name of the product.')
                ->required(),

            'sku' => $schema->string()
                ->description('Unique stock keeping unit identifier.')
                ->required(),

            'description' => $schema->string()
                ->description('Optional product description.')
                ->nullable(),

            'unit_price' => $schema->number()
                ->description('Unit price of the product.')
                ->required(),

            'stock_quantity' => $schema->integer()
                ->description('Available stock quantity.')
                ->required(),

            'is_active' => $schema->boolean()
                ->description('Whether the product is active.')
                ->default(true),
        ];
    }
}
