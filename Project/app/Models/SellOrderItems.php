<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellOrderItems extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sell_order_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($item) {
        if ($item->product && $item->unit_price === null) {
            $item->unit_price = $item->product->unit_price;
        }

        // Calculate line total
        $item->line_total = $item->quantity * $item->unit_price;
        });
    }

    public function sellorder()
    {
        return $this->belongsTo(SellOrders::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
