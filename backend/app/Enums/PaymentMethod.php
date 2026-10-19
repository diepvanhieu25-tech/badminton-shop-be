<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD = 'cod';
    case VNPAY = 'vnpay';
    case MOMO = 'momo';
    case BANK_TRANSFER = 'bank_transfer';

    public function label(): string
    {
        return match($this) {
            self::COD => 'Thanh toán khi nhận hàng (COD)',
            self::VNPAY => 'Ví VNPay',
            self::MOMO => 'Ví MoMo',
            self::BANK_TRANSFER => 'Chuyển khoản VietQR',
        };
    }
}