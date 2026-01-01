<?php

namespace App\Enums;

enum CartStatus: string
{
    case ACTIVE = 'active';         // Đang mua sắm
    case ABANDONED = 'abandoned';   // Bỏ quên giỏ hàng (để gửi mail nhắc)
    case CONVERTED = 'converted';   // Đã thanh toán thành order
}