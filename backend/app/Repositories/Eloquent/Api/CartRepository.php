<?php

namespace App\Repositories\Eloquent\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Repositories\Interfaces\Api\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function findActiveCart(int $userId)
    {
        return Cart::where('user_id', $userId)
            ->where('status', 'active')
            // Load sâu: Items -> Variant -> Product
            ->with([
                'items' => function ($query) {
                    $query->orderBy('created_at', 'desc'); // Item mới thêm lên đầu
                },
                'items.variant',            // Lấy thông tin biến thể (giá, tồn kho)
                'items.variant.product'     // Lấy tên, ảnh gốc của sản phẩm
            ])
            ->first();
    }

    public function firstOrCreateCart(int $userId)
    {
        // Tìm giỏ hàng active, nếu không có thì tạo mới
        return Cart::firstOrCreate(
            ['user_id' => $userId, 'status' => 'active']
        );
    }

    public function findCartItem($cartId, $variantId)
    {
        return CartItem::where('cart_id', $cartId)
            ->where('product_variant_id', $variantId)
            ->first();
    }

    public function getVariantStock($variantId)
    {
        return ProductVariant::find($variantId);
    }

    public function createOrUpdateItem($cartId, $variantId, $quantity, $isUpdate = false)
    {
        // isUpdate = true: Ghi đè số lượng (dùng cho chức năng Update)
        // isUpdate = false: Cộng dồn số lượng (dùng cho chức năng Add)

        $item = $this->findCartItem($cartId, $variantId);

        if ($item) {
            // Nếu đã có -> Cộng dồn hoặc cập nhật
            $item->quantity = $isUpdate ? $quantity : ($item->quantity + $quantity);
            $item->save();
            return $item;
        }

        // Nếu chưa có -> Tạo mới
        return CartItem::create([
            'cart_id'            => $cartId,
            'product_variant_id' => $variantId,
            'quantity'           => $quantity
        ]);
    }

    public function findItemById(int $itemId)
    {
        // Load cả quan hệ cart và variant để check quyền + tồn kho
        return CartItem::with(['cart', 'variant'])->find($itemId);
    }

    public function deleteItem(int $itemId)
    {
        return CartItem::destroy($itemId);
    }

    public function clearCart(int $cartId)
    {
        // Xóa tất cả item thuộc cart này
        return CartItem::where('cart_id', $cartId)->delete();
    }

    public function updateSelection(int $cartId, array $itemIds, bool $isSelected)
    {
        // Update hàng loạt, NHƯNG chỉ update những item thuộc về cartId này (Bảo mật)
        return CartItem::where('cart_id', $cartId)
            ->whereIn('id', $itemIds)
            ->update(['is_selected' => $isSelected]);
    }
}
