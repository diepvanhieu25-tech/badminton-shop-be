<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function findActiveCartByUserId(int $userId)
    {
        // Lấy giỏ hàng đang active
        // Load sâu: items -> variant -> product (để lấy tên sp và ảnh)
        return Cart::where('user_id', $userId)
            ->where('status', 'active')
            ->with([
                'items.variant.product'
            ])
            ->first();
    }
}
