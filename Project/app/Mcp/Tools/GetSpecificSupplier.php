<?php

namespace App\Mcp\Tools;

use App\Http\Resources\SuppliersResource;
use App\Models\Suppliers;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get a single supplier')]
class GetSpecificSupplier extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $supplier = Suppliers::findOrFail($id);

        return Response::json(
            (new SuppliersResource($supplier))->resolve()
        );
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the supplier to retrieve.')
                ->required(),
        ];
    }
}
