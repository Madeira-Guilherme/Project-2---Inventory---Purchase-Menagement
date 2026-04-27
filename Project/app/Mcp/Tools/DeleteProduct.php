<?php

namespace App\Mcp\Tools;

use App\Models\Products;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Delete a product')]
class DeleteProduct extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $product = Products::findOrFail($id);

        $product->delete();

        return Response::json([
            'message' => 'Product deleted successfully',
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the product to delete.')
                ->required(),
        ];
    }
}
