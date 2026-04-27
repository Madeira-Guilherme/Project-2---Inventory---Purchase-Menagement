<?php

namespace App\Mcp\Resources;

use App\Http\Resources\SuppliersResource;
use App\Models\Suppliers;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use League\Uri\UriTemplate;

#[Description('Get all suppliers')]
class GetSuppliers extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $suppliers = Suppliers::query()->get();

        return Response::json(
            SuppliersResource::collection($suppliers)->resolve()
        );
    }

    /**
     * Define the URI template.
     */
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('/api/suppliers');
    }
}
