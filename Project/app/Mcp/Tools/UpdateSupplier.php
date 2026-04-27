<?php

namespace App\Mcp\Tools;

use App\Http\Resources\SuppliersResource;
use App\Models\Suppliers;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Update an existing supplier')]
class UpdateSupplier extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $id = $request->get('id');

        $supplier = Suppliers::findOrFail($id);

        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'is_active'    => 'sometimes|boolean',
        ]);

        $supplier->update($validated);

        return Response::json([
            'message' => 'Supplier updated successfully',
            'data' => (new SuppliersResource($supplier->fresh()))->resolve(),
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID of the supplier to update.')
                ->required(),

            'company_name' => $schema->string()
                ->description('Company name.')
                ->nullable(),

            'contact_name' => $schema->string()
                ->description('Contact person name.')
                ->nullable(),

            'email' => $schema->string()
                ->description('Supplier email address.')
                ->nullable(),

            'phone' => $schema->string()
                ->description('Supplier phone number.')
                ->nullable(),

            'address' => $schema->string()
                ->description('Supplier address.')
                ->nullable(),

            'is_active' => $schema->boolean()
                ->description('Whether the supplier is active.')
                ->nullable(),
        ];
    }
}
