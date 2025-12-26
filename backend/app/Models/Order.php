<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'code', // Mã đơn hàng #ORD...
        'receiver_name',
        'receiver_phone',
        'shipping_address',
        'note',
        'subtotal',
        'shipping_fee',
        'total',
        'payment_method', // cod, vnpay...
        'payment_status', // unpaid, paid...
        'status',         // pending, processing...
    ];

    // Format số tiền khi lấy ra (Optional, nếu muốn auto ép kiểu)
    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Một đơn hàng có nhiều sản phẩm
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Một đơn hàng có thông tin thanh toán (thường là 1-1 hoặc 1-nhiều, ở đây làm 1-nhiều cho chắc nhưng thường lấy cái mới nhất)
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}
