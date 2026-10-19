<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_url',
        'sort_order',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getResolvedUrlAttribute(): ?string
    {
        if (!$this->image_url) {
            return null;
        }
        return filter_var($this->image_url, FILTER_VALIDATE_URL)
            ? $this->image_url
            : \Illuminate\Support\Facades\Storage::url($this->image_url);
    }
}