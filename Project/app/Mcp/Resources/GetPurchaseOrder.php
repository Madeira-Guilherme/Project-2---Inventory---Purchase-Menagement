<?php

namespace App\Mcp\Resources;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Resource;
use League\Uri\UriTemplate;

#[Description('Get all purchase orders with filters and pagination')]
class GetPurchaseOrder extends Resource
{
    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $perPage = (int) $request->get('per_page', 15);

        // safety cap
        $perPage = $perPage > 0 ? min($perPage, 20) : 15;

        $query = PurchaseOrders::with('items.product')
            ->when($request->get('status'), fn($q) => $q->where('status', $request->get('status')))
            ->when($request->get('supplier_id'), fn($q) => $q->where('supplier_id', $request->get('supplier_id')))
            ->when($request->get('from_date'), fn($q) => $q->whereDate('ordered_at', '>=', $request->get('from_date')))
            ->when($request->get('to_date'), fn($q) => $q->whereDate('ordered_at', '<=', $request->get('to_date')))
            ->when($request->get('created_by'), fn($q) => $q->where('created_by', $request->get('created_by')))
            ->when($request->get('min_total'), fn($q) => $q->where('total_amount', '>=', $request->get('min_total')))
            ->when($request->get('max_total'), fn($q) => $q->where('total_amount', '<=', $request->get('max_total')));

        $orders = $query->paginate($perPage);

        return Response::json([
            'current_page' => $orders->currentPage(),
            'data' => PurchaseOrderResource::collection($orders->items())->resolve(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
        ]);
    }

    /**
     * Define the URI template.
     */
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('/api/purchaseorders{?status,supplier_id,from_date,to_date,created_by,min_total,max_total,per_page,page}');
    }
}
