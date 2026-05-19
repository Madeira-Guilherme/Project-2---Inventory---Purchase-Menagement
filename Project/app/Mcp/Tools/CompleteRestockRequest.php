<?php

namespace App\Mcp\Tools;

use App\Models\RestockRequest;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Mark a restock request as completed.')]
class CompleteRestockRequest extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->all();

        $restockRequest = RestockRequest::find($data['restock_request_id']);

        if (! $restockRequest) {
            return Response::json([
                'success' => false,
                'message' => 'Restock request not found.',
            ]);
        }

        $restockRequest->update([
            'completed' => true,
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Restock request marked as completed.',
            'data' => [
                'id'         => $restockRequest->id,
                'completed'  => $restockRequest->completed,
                'updated_at' => $restockRequest->updated_at,
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
            'restock_request_id' => $schema->integer()
                ->description('The ID of the restock request to complete.')
                ->required(),
        ];
    }
}
