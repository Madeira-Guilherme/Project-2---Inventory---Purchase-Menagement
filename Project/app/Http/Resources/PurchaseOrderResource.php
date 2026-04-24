<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
public function toArray($request)
{
    return [
        'id' => $this->id,
        'supplier_id' => $this->supplier_id,
        'order_number' => $this->order_number,
        'status' => $this->status,

        'total_amount' => $this->total_amount,

        // Dates
        'ordered_at' => $this->ordered_at?->diffForHumans(),

        'received_at' => $this->received_at?->diffForHumans(),

        'created_by' => $this->created_by,

        // Timestamps
        'created_at' => $this->created_at?->diffForHumans(),
        'updated_at' => $this->updated_at?->diffForHumans(),
        'deleted_at' => $this->deleted_at?->diffForHumans(),

        'items' => $this->whenLoaded('items', function () {
            return $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->quantity*$item->product->unit_price,

                    'product' => $item->product ? [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'unit_price' => $item->product->unit_price,
                    ] : null,
                ];
            });
        }),
    ];
}
}
