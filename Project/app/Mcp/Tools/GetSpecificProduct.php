<?php

namespace App\Mcp\Tools;

use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get a single product from the warehouse server')]
class GetSpecificProduct extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $product = Products::findOrFail($id);

        return Response::json(
            (new ProductsResource($product))->resolve()
        );
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the product to retrieve.')
                ->required(),
        ];
    }
}
