<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';       // Cho Order
    case PENDING = 'pending';     // Cho Transaction
    case PAID = 'paid';           // Cho Order
    case SUCCESS = 'success';     // Cho Transaction
    case FAILED = 'failed';       // Chung

    // Helper kiểm tra trạng thái thành công
    public function isPaid(): bool
    {
        return $this === self::PAID || $this === self::SUCCESS;
    }
}