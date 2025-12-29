<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
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
            // Lấy ID user đang đăng nhập (nhờ middleware auth:sanctum)
            $userId = Auth::id();

            $cart = $this->cartService->getMyCart($userId);

            if (!$cart) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Giỏ hàng trống',
                    'data'    => []
                ], 200);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Lấy thông tin giỏ hàng thành công',
                'data'    => new CartResource($cart)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
