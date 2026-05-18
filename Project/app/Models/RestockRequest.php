<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestockRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'requester_id',
        'product_id',
        'reason',
        'completed',
    ];

    /**
     * User who requested the restock.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Product being requested for restock.
     */
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
