<?php

namespace App\Mcp\Resources;

use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use League\Uri\UriTemplate;

#[Description('Get all products with optional stock filters')]
class GetProducts extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $query = Products::query();

        if ($request->has('min_stock')) {
            $query->where('stock_quantity', '>=', $request->get('min_stock'));
        }

        if ($request->has('max_stock')) {
            $query->where('stock_quantity', '<=', $request->get('max_stock'));
        }

        $products = $query->get();

        return Response::json(
            ProductsResource::collection($products)->resolve()
        );
    }

    /**
     * Define the URI template for this resource.
     */
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('/api/products{?min_stock,max_stock}');
    }
}
