<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'payment_intent_id',
        'session_id',
        'amount',
        'currency',
        'customer_email',
        'customer_name',
        'status',
        'products',
        'refund_reason',
        'refunded_at',
    ];

    protected $casts = [
        'products' => 'array',
        'refunded_at' => 'datetime',
    ];
}
