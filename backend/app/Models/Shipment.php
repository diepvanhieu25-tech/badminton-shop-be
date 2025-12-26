<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tracking_code', // Mã vận đơn (VD: GHN_12345)
        'carrier',       // Tên đơn vị vận chuyển
        'status',        // pending, shipping, delivered...
        'cod_amount',    // Tiền thu hộ
        'note',          // Ghi chú giao hàng
        'shipped_at',    // Thời gian bắt đầu giao
        'delivered_at',  // Thời gian khách nhận hàng
    ];

    protected $casts = [
        // Chuyển string ngày tháng trong DB thành đối tượng Carbon (Date)
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cod_amount' => 'decimal:2',
    ];

    // Quan hệ: Thuộc về 1 Đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}