<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuppliersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
{
    return [
        'id' => $this->id,
        'company_name' => $this->company_name,
        'contact_name' => $this->contact_name,
        'email' => $this->email,
        'phone' => $this->phone,
        'address' => $this->address,

        'is_active' => (bool) $this->is_active,

        // Timestamps
        'created_at' => $this->created_at?->diffForHumans(),
        'updated_at' => $this->updated_at?->diffForHumans(),
        'deleted_at' => $this->deleted_at?->diffForHumans(),
    ];
}
}
