<?php

namespace App\Mcp\Tools;

use App\Models\Suppliers;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new supplier')]
class CreateSupplier extends Tool
{
    /**
     * Handle the tool execution.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'is_active'    => 'boolean',
        ]);

        $supplier = Suppliers::create($validated);

        return Response::json([
            'message' => 'Supplier created successfully',
            'data' => $supplier,
        ], 201);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'company_name' => $schema->string()
                ->description('Company name.')
                ->required(),

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
                ->default(true),
        ];
    }
}
