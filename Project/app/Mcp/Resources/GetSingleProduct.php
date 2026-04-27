<?php

namespace App\Mcp\Resources;

use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use League\Uri\UriTemplate;

#[Description('Get a single product from the warehouse server')]
class GetSingleProduct extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
{
    $id = $request->get('product');

    $product = Products::findOrFail($id);

    return Response::json(
        (new ProductsResource($product))->resolve()
    );
}

    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('/api/products/{product}');
    }

}
