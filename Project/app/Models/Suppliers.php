<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suppliers extends Model
{

use HasFactory, SoftDeletes;
    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrders::class);
    }
}
