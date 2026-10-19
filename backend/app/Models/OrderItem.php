<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_name', // Lưu cứng tên lúc mua
        'variant_name', // Lưu cứng tên biến thể lúc mua
        'attributes',   // Snapshot thuộc tính biến thể (size, color, weight, grip...)
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'attributes' => 'array',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Vẫn giữ liên kết với biến thể gốc (để check lại nếu cần), nhưng cho phép null (nếu sp bị xóa)
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')->withTrashed();
    }
}