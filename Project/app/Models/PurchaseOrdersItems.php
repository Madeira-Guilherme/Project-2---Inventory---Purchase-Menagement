<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PurchaseOrders;

class PurchaseOrdersItems extends Model
{

use HasFactory;
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'line_total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    // Relationships
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrders::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
