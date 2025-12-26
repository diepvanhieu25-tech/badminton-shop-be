<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'sku',
        'thumbnail',
        'description',
        'price',
        'original_price',
        'status',
        'has_variants',
        'is_featured',
    ];

    // Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Brand
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    // Các biến thể (Size/Màu)
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Album ảnh
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
}