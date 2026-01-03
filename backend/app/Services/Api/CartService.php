<?php

namespace App\Services\Api;

use App\Repositories\Interfaces\Api\CartRepositoryInterface;
use Exception;

class CartService
{
    protected $cartRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function getMyCart(int $userId)
    {
        return $this->cartRepo->findActiveCart($userId);
    }

    public function addToCart(int $userId, array $data)
    {
        $variantId = $data['product_variant_id'];
        $qtyRequest = $data['quantity'];

        // 1. Kiểm tra tồn kho
        $variant = $this->cartRepo->getVariantStock($variantId);
        if (!$variant) {
            throw new Exception("Sản phẩm không tồn tại.");
        }

        // 2. Lấy hoặc tạo giỏ hàng cho user
        $cart = $this->cartRepo->firstOrCreateCart($userId);

        // 3. Kiểm tra logic cộng dồn số lượng
        $existingItem = $this->cartRepo->findCartItem($cart->id, $variantId);
        $currentQtyInCart = $existingItem ? $existingItem->quantity : 0;

        // Tổng số lượng sau khi thêm
        $totalQty = $currentQtyInCart + $qtyRequest;

        if ($totalQty > $variant->stock_qty) {
            throw new Exception("Kho chỉ còn {$variant->stock_qty} sản phẩm. Bạn đã có {$currentQtyInCart} trong giỏ.");
        }

        // 4. Lưu vào DB
        return $this->cartRepo->createOrUpdateItem($cart->id, $variantId, $qtyRequest, false);
    }
}
