<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Update an existing product')]
class UpdateProduct extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $product = Products::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:255|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'unit_price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        // Normalize boolean if sent as string (true/false/"1"/"0")
        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var(
                $request->get('is_active'),
                FILTER_VALIDATE_BOOLEAN
            );
        }

        $product->update($validated);

        return Response::json([
            'message' => 'Product updated successfully',
            'data' => (new ProductsResource($product->fresh()))->resolve(),
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the product to update.')
                ->required(),

            'name' => $schema->string()
                ->description('Product name.')
                ->nullable(),

            'sku' => $schema->string()
                ->description('Stock keeping unit.')
                ->nullable(),

            'description' => $schema->string()
                ->description('Product description.')
                ->nullable(),

            'unit_price' => $schema->number()
                ->description('Unit price of the product.')
                ->nullable(),

            'stock_quantity' => $schema->integer()
                ->description('Available stock quantity.')
                ->nullable(),

            'is_active' => $schema->boolean()
                ->description('Whether the product is active.')
                ->nullable(),
        ];
    }
}
