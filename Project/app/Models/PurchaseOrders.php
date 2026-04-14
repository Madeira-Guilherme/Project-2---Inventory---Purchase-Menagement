<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrders extends Model
{

use HasFactory;
    protected $fillable = [
        'supplier_id',
        'order_number',
        'status',
        'total_amount',
        'ordered_at',
        'received_at',
        'created_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'ordered_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrdersItems::class, 'purchase_order_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
