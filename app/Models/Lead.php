<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'business_name',
        'business_type',
        'whatsapp',
        'status',
        'notes',
    ];

    protected $attributes = [
        'status' => 'new',
    ];
}
