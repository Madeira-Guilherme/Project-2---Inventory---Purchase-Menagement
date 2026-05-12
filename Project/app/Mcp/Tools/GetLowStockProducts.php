<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get every product where the stock is below a certain threshold')]
class GetLowStockProducts extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $stockQuantity = $request->get('stock_quantity');

        $products = Products::query()
            ->where('stock_quantity', '<=', $stockQuantity)
            ->where('is_active', true)
            ->get();

        return Response::json([
            'count' => $products->count(),
            'data' => ProductsResource::collection($products)->resolve(),
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'stock_quantity' => $schema->integer()
                ->description('Maximum stock quantity to include')
                ->required(),
        ];
    }
}
