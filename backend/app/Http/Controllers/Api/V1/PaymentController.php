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

    // API tạo URL (Frontend gọi để lấy link redirect)
    public function createVnpayUrl(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'order_code' => 'required|string|exists:orders,code'
            ]);

            $user = Auth::user();
            if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

            $url = $this->vnpayService->createPaymentUrl($user->id, $request->order_code);

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

    public function vnpayCallback(Request $request)
    {
        try {
            $result = $this->vnpayService->handlePaymentCallback($request->all());

            // URL Frontend http://localhost:3000/payment-result
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/payment/result';

            // Tạo query params để gửi kết quả về Frontend hiển thị
            $queryParams = http_build_query([
                'success' => $result['success'] ? '1' : '0',
                'order_code' => $result['order_code'] ?? '',
                'message' => $result['message']
            ]);

            // Chuyển hướng trình duyệt về Frontend
            return redirect("{$frontendUrl}?{$queryParams}");
        } catch (\Exception $e) {
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/payment/result';
            return redirect("{$frontendUrl}?success=0&message=" . urlencode('Lỗi hệ thống'));
        }
    }
}
