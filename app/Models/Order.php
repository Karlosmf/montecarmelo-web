<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'total',
        'items',
        'status',
        'notes',
        'created_at', // Allow seeding custom dates
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'integer',
        'created_at' => 'datetime',
    ];
}
