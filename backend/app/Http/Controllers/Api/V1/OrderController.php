<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Http\Resources\Api\Order\OrderResource;
use App\Services\Api\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1;
            $limit = $request->get('limit', 10);

            $orders = $this->orderService->getMyOrders($userId, $limit);

            return response()->json([
                'status'  => true,
                'message' => 'Lấy danh sách đơn hàng thành công',
                'data'    => OrderResource::collection($orders)->response()->getData(true)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($code): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1;

            $order = $this->orderService->getMyOrderDetail($userId, $code);

            return response()->json([
                'status'  => true,
                'message' => 'Lấy chi tiết đơn hàng thành công',
                'data'    => new OrderResource($order)
            ], 200);
        } catch (\Exception $e) {
            $codeStatus = $e->getCode() == 404 ? 404 : 500;
            return response()->json(['status' => false, 'message' => $e->getMessage()], $codeStatus);
        }
    }
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1; // Hardcode id=1 nếu đang test tắt auth

            $order = $this->orderService->createOrder($userId, $request->validated());

            return response()->json([
                'status'  => true,
                'message' => 'Đặt hàng thành công',
                'data'    => new OrderResource($order),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function cancel($code): JsonResponse
    {
        try {
            $userId = Auth::id() ?? 1; // Hardcode test nếu tắt Auth
            
            $order = $this->orderService->cancelOrder($userId, $code);

            return response()->json([
                'status'  => true,
                'message' => 'Hủy đơn hàng thành công',
                'data'    => new OrderResource($order)
            ], 200);

        } catch (\Exception $e) {
            // Trả về 404 nếu không tìm thấy, 400 nếu lỗi logic (sai trạng thái)
            $codeStatus = $e->getCode() == 404 ? 404 : 400;
            
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $codeStatus);
        }
    }
}
