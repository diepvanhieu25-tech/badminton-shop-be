<?php

namespace App\Services;

use App\Repositories\Interfaces\CartRepositoryInterface;

class CartService
{
    protected $cartRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function getMyCart($userId)
    {
        $cart = $this->cartRepo->findActiveCartByUserId($userId);

        // Nếu chưa có giỏ hàng thì trả về null hoặc mảng rỗng tùy logic frontend
        return $cart;
    }
}
