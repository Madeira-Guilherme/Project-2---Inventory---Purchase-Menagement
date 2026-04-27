<?php

namespace App\Mcp\Tools;

use App\Models\Products;
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
        $id = $request->get('product');

        $product = Products::findOrFail($id);

        $product->delete();

        return Response::json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
