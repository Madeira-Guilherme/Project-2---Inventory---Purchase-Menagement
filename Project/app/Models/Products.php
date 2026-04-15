<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'sku',
        'description',
        'unit_price',
        'stock_quantity',
        'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrdersItems::class);
    }
}
