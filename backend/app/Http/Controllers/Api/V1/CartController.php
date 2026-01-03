<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Http\Requests\Api\Cart\SelectCartItemRequest;
use App\Http\Requests\Api\Cart\UpdateCartItemRequest;
use App\Services\Api\CartService;
use App\Http\Resources\Api\CartResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1;
            $cart = $this->cartService->getMyCart($userId);

            // Nếu chưa có giỏ hàng, trả về format rỗng chuẩn thay vì null
            if (!$cart) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Giỏ hàng trống',
                    'data'    => [
                        'id' => null,
                        'total_items' => 0,
                        'total_price' => 0,
                        'items' => []
                    ]
                ], 200);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Lấy giỏ hàng thành công',
                'data'    => new CartResource($cart)
            ], 200);
        } catch (\Exception $e) {
            // Log lỗi để debug
            \Illuminate\Support\Facades\Log::error('Cart Error: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Đã có lỗi xảy ra, vui lòng thử lại.'
            ], 500);
        }
    }

    public function addToCart(AddToCartRequest $request): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1;
            // Service nên trả về item vừa thêm hoặc tổng số lượng item để FE update badge icon
            $cartInfo = $this->cartService->addToCart($userId, $request->validated());

            return response()->json([
                'status'  => true,
                'message' => 'Thêm vào giỏ hàng thành công',
                'data'    => [
                    'total_items' => $cartInfo['total_items'] // Trả về số này để FE update số trên icon giỏ hàng ngay lập tức
                ]
            ], 201); // 201 Created chuẩn hơn 200

        } catch (\InvalidArgumentException $e) {
            // Lỗi logic (ví dụ: hết hàng) -> ném InvalidArgumentException từ Service
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            // Lỗi hệ thống thực sự
            \Illuminate\Support\Facades\Log::error('Cart Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Lỗi hệ thống'], 500);
        }
    }

    public function updateItem(UpdateCartItemRequest $request, $itemId): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1; // HARDCODE CHO TEST

            $this->cartService->updateItemQty($userId, (int)$itemId, $request->quantity);

            return response()->json([
                'status'  => true,
                'message' => 'Cập nhật số lượng thành công',
                'data'    => null // Hoặc trả về CartResource mới nhất nếu muốn
            ], 200);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 500;
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $code >= 100 && $code < 600 ? $code : 500);
        }
    }

    public function removeItem($itemId): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1; // HARDCODE CHO TEST

            $this->cartService->removeItem($userId, (int)$itemId);

            return response()->json([
                'status'  => true,
                'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công',
            ], 200);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 500;
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $code >= 100 && $code < 600 ? $code : 500);
        }
    }

    public function clear(): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1; // Hardcode test
            $this->cartService->clearCart($userId);

            return response()->json([
                'status'  => true,
                'message' => 'Đã làm trống giỏ hàng',
                'data'    => null
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateSelection(SelectCartItemRequest $request): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1; // Hardcode test
            
            // Service trả về Cart mới (đã tính lại tiền)
            $updatedCart = $this->cartService->updateSelection(
                $userId, 
                $request->item_ids, 
                $request->selected
            );

            return response()->json([
                'status'  => true,
                'message' => 'Cập nhật lựa chọn thành công',
                'data'    => new CartResource($updatedCart)
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
