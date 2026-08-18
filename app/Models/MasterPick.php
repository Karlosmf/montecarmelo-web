<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPick extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'kicker',
        'image_path',
        'recommendation',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function displayTitle(): string
    {
        return $this->title ?: ($this->product?->name ?? 'Selección');
    }

    public function displayImage(): ?string
    {
        return $this->image_path ?: $this->product?->image_path;
    }
}
