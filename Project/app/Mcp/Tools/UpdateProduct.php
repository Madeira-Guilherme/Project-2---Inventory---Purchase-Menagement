<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
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
        $id = $request->get('product');

        $product = Products::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:255|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'unit_price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var($request->get('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        $product->update($validated);

        return Response::json([
            'message' => 'Product updated successfully',
            'data' => (new ProductsResource($product->fresh()))->resolve(),
        ]);
    }
}
