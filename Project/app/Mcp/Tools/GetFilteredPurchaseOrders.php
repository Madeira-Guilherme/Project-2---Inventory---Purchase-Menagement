<?php

namespace App\Mcp\Tools;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrders;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get all purchase orders with filters and pagination')]
class GetFilteredPurchaseOrders extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $perPage = (int) $request->get('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 20) : 15;

        $page = (int) $request->get('page', 1);

        $query = PurchaseOrders::with('items.product')
            ->when($request->get('status'), fn ($q, $v) => $q->where('status', $v))
            ->when($request->get('supplier_id'), fn ($q, $v) => $q->where('supplier_id', $v))
            ->when($request->get('from_date'), fn ($q, $v) => $q->whereDate('ordered_at', '>=', $v))
            ->when($request->get('to_date'), fn ($q, $v) => $q->whereDate('ordered_at', '<=', $v))
            ->when($request->get('created_by'), fn ($q, $v) => $q->where('created_by', $v))
            ->when($request->get('min_total'), fn ($q, $v) => $q->where('total_amount', '>=', $v))
            ->when($request->get('max_total'), fn ($q, $v) => $q->where('total_amount', '<=', $v));

        $orders = $query->paginate($perPage, ['*'], 'page', $page);

        return Response::json([
            'current_page' => $orders->currentPage(),
            'data' => PurchaseOrderResource::collection($orders->items())->resolve(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
        ]);
    }

    /**
     * Get the tool's input schema.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'status' => $schema->string()
                ->description('Filter by order status.')
                ->nullable(),

            'supplier_id' => $schema->integer()
                ->description('Filter by supplier ID.')
                ->nullable(),

            'from_date' => $schema->string()
                ->description('Filter orders from date (YYYY-MM-DD).')
                ->nullable(),

            'to_date' => $schema->string()
                ->description('Filter orders to date (YYYY-MM-DD).')
                ->nullable(),

            'created_by' => $schema->integer()
                ->description('Filter by user who created the order.')
                ->nullable(),

            'min_total' => $schema->number()
                ->description('Minimum total amount.')
                ->nullable(),

            'max_total' => $schema->number()
                ->description('Maximum total amount.')
                ->nullable(),

            'per_page' => $schema->integer()
                ->description('Number of results per page (max 20).')
                ->default(15),

            'page' => $schema->integer()
                ->description('Page number for pagination.')
                ->default(1),
        ];
    }
}
