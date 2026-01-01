<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPING = 'shipping';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xác nhận',
            self::PROCESSING => 'Đang xử lý',
            self::SHIPPING => 'Đang giao hàng',
            self::COMPLETED => 'Hoàn thành',
            self::CANCELLED => 'Đã hủy',
            self::REFUNDED => 'Đã hoàn tiền',
        };
    }

    // Trả về class màu sắc (hỗ trợ hiển thị badge trên Admin)
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning', // yellow
            self::PROCESSING => 'info', // blue
            self::SHIPPING => 'primary', // indigo
            self::COMPLETED => 'success', // green
            self::CANCELLED, self::REFUNDED => 'danger', // red
        };
    }
}