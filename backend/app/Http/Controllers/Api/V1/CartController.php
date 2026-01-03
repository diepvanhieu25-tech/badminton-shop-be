<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddToCartRequest;
use App\Services\Api\CartService;
use App\Http\Resources\CartResource;
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
            // Lấy User ID từ Token (Sanctum)
            $userId = Auth::id();

            $cart = $this->cartService->getMyCart($userId);

            // Trường hợp user mới chưa từng mua gì
            if (!$cart) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Giỏ hàng trống',
                    'data'    => null // Hoặc trả về object rỗng tùy quy ước với FE
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
            $userId = Auth::id();

            $this->cartService->addToCart($userId, $request->validated());

            return response()->json([
                'status'  => true,
                'message' => 'Thêm vào giỏ hàng thành công',
                // Trả về null hoặc gọi lại getMyCart nếu muốn FE cập nhật ngay giỏ hàng
                'data'    => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage() // Trả về lỗi như: "Kho chỉ còn 5 sản phẩm..."
            ], 400); // 400 Bad Request
        }
    }
}
