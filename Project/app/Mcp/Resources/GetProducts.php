<?php

namespace App\Mcp\Resources;

use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use League\Uri\UriTemplate;

#[Description('Get all suppliers')]
class GetProducts extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $products = Products::query()->get();

        return Response::json(
            ProductsResource::collection($products)->resolve()
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
