<?php

namespace App\Mcp\Tools;

use App\Models\RestockRequest;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new restock request for a product.')]
class CreateRestockRequest extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->all();

        $restockRequest = RestockRequest::create([
            'requester_id' => $data['requester_id'],
            'product_id'   => $data['product_id'],
            'reason'       => $data['reason'] ?? null,
            'completed'    => false,
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Restock request created successfully.',
            'data'    => [
                'id'            => $restockRequest->id,
                'requester_id'  => $restockRequest->requester_id,
                'product_id'    => $restockRequest->product_id,
                'reason'        => $restockRequest->reason,
                'completed'     => $restockRequest->completed,
                'created_at'    => $restockRequest->created_at,
            ],
        ]);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'requester_id' => $schema->integer()
                ->description('The ID of the user creating the restock request.')
                ->required(),

            'product_id' => $schema->integer()
                ->description('The ID of the product to restock.')
                ->required(),

            'reason' => $schema->string()
                ->description('Optional reason for the restock request.')
                ->nullable(),
        ];
    }
}
