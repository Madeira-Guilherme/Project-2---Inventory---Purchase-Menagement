<?php

namespace App\Mcp\Tools;

use App\Models\Suppliers;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Delete a supplier')]
class DeleteSupplier extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $supplier = Suppliers::findOrFail($id);

        $supplier->delete();

        return Response::json([
            'message' => 'Supplier deleted successfully',
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the supplier to delete.')
                ->required(),
        ];
    }
}
