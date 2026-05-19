<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestockRequestResource extends JsonResource
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

            'requester_id' => $this->requester_id,

            'product_id' => $this->product_id,

            'reason' => $this->reason,

            'completed' => $this->completed,

            // Requester
            'requester' => $this->whenLoaded('requester', function () {
                return [
                    'id' => $this->requester->id,
                    'name' => $this->requester->name,
                    'email' => $this->requester->email,
                ];
            }),

            // Product
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'unit_price' => $this->product->unit_price,
                ];
            }),

            // Timestamps
            'created_at' => $this->created_at?->diffForHumans(),
            'updated_at' => $this->updated_at?->diffForHumans(),
            'deleted_at' => $this->deleted_at?->diffForHumans(),
        ];
    }
}
