<?php

namespace App\Services\Api;

use App\Repositories\Interfaces\Api\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;
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
        return DB::transaction(function () use ($userId, $data) {
            $variantId = $data['product_variant_id'];
            $qtyRequest = $data['quantity'];

            // 1. Check kho (Repository nên trả về object Variant)
            $variant = $this->cartRepo->getVariantStock($variantId);

            // CHECK THÊM: Nếu sản phẩm cha bị xóa mềm hoặc ẩn thì sao?
            // Cần đảm bảo variant->product tồn tại và đang active.
            if (!$variant || !$variant->product) {
                throw new \InvalidArgumentException("Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.");
            }

            // 2. Tạo Cart
            $cart = $this->cartRepo->firstOrCreateCart($userId);

            // 3. Logic số lượng
            $existingItem = $this->cartRepo->findCartItem($cart->id, $variantId);
            $currentQtyInCart = $existingItem ? $existingItem->quantity : 0;

            if (($currentQtyInCart + $qtyRequest) > $variant->stock_qty) {
                throw new \InvalidArgumentException("Kho chỉ còn {$variant->stock_qty} sản phẩm.");
            }

            // 4. Save
            $this->cartRepo->createOrUpdateItem($cart->id, $variantId, $qtyRequest, false);

            // OPTIONAL: Trả về số lượng item hiện tại để Controller trả về FE
            return ['total_items' => $cart->items()->count()]; // Hoặc sum('quantity')
        });
    }

    public function updateItemQty(int $userId, int $itemId, int $newQty)
    {
        // 1. Tìm Cart Item
        $item = $this->cartRepo->findItemById($itemId);

        // 2. Validate: Item có tồn tại không?
        if (!$item) {
            throw new Exception("Sản phẩm trong giỏ không tồn tại.", 404);
        }

        // 3. SECURITY CHECK: Item này có thuộc về User đang đăng nhập không?
        if ($item->cart->user_id !== $userId) {
            throw new Exception("Bạn không có quyền sửa giỏ hàng này.", 403);
        }

        // 4. Validate Kho: Check số lượng mới có đủ hàng không
        if ($item->variant && $newQty > $item->variant->stock_qty) {
            throw new Exception("Kho chỉ còn {$item->variant->stock_qty} sản phẩm.", 400);
        }

        // 5. Cập nhật
        $item->quantity = $newQty;
        $item->save();

        return $item;
    }

    public function removeItem(int $userId, int $itemId)
    {
        $item = $this->cartRepo->findItemById($itemId);

        if (!$item) {
            throw new Exception("Sản phẩm không tồn tại.", 404);
        }

        // SECURITY CHECK
        if ($item->cart->user_id !== $userId) {
            throw new Exception("Bạn không có quyền xóa sản phẩm này.", 403);
        }

        return $this->cartRepo->deleteItem($itemId);
    }

    public function clearCart(int $userId)
    {
        $cart = $this->cartRepo->findActiveCart($userId);
        if ($cart) {
            $this->cartRepo->clearCart($cart->id);
        }
    }

    public function updateSelection(int $userId, array $itemIds, bool $isSelected)
    {
        $cart = $this->cartRepo->findActiveCart($userId);
        
        if (!$cart) {
            throw new Exception("Giỏ hàng không tồn tại", 404);
        }

        // Gọi Repo update
        $this->cartRepo->updateSelection($cart->id, $itemIds, $isSelected);
        
        // Trả về cart mới nhất để FE cập nhật lại tổng tiền (vì tổng tiền thay đổi khi chọn/bỏ chọn)
        return $this->cartRepo->findActiveCart($userId);
    }
}
