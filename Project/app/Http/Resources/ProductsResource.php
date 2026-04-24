<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
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
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,

            'unit_price' => $this->unit_price,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,

            // Timestamps
            'created_at' => $this->created_at?->diffForHumans(),
            'updated_at' => $this->updated_at?->diffForHumans(),
            'deleted_at' => $this->deleted_at?->diffForHumans(),
        ];
    }
}
