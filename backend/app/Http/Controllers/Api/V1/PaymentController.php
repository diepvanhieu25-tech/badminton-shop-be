<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\VnpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected VnpayService $vnpayService
    ) {}

    // POST /v1/payment/vnpay/create-url
    public function createVnpayUrl(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'order_code' => 'required|string|exists:orders,code'
            ]);
            
            $userId = Auth::id() ?? 1; // Hardcode test
            
            $url = $this->vnpayService->createPaymentUrl($userId, $request->order_code);

            return response()->json([
                'status'  => true,
                'message' => 'Tạo URL thanh toán thành công',
                'data'    => ['url' => $url] 
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // GET /v1/payment/vnpay/callback
    public function vnpayCallback(Request $request): JsonResponse
    {
        try {
            $result = $this->vnpayService->handlePaymentCallback($request->all());

            return response()->json([
                'status'  => $result['success'],
                'message' => $result['message'],
                'data'    => $result
            ], $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }
}