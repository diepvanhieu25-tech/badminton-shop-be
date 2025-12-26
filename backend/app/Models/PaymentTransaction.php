<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'transaction_code', // Mã giao dịch từ Momo/VNPAY
        'status',           // pending, success, failed
        'amount',           // Số tiền của giao dịch này
        'raw_data',         // Dữ liệu JSON gốc từ cổng thanh toán
    ];

    protected $casts = [
        // Tự động chuyển JSON trong DB thành Array khi gọi trong code
        'raw_data' => 'array', 
        'amount' => 'decimal:2',
    ];

    // Quan hệ: Thuộc về 1 Payment cha
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}