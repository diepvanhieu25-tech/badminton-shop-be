<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'attributes', // JSON
        'price',
        'original_price',
        'stock_qty',
        'stock_alert',
        'image',
    ];

    // Tự động chuyển JSON string <-> Array
    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}