<?php

namespace App\Services\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Repositories\Interfaces\Api\CartRepositoryInterface;
use App\Repositories\Interfaces\Api\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepo,
        protected OrderRepositoryInterface $orderRepo
    ) {}

    public function createOrder(int $userId, array $data)
    {
        return DB::transaction(function () use ($userId, $data) {
            // 1. Lấy giỏ hàng & Item được chọn
            $cart = $this->cartRepo->findActiveCart($userId);
            if (!$cart) throw new Exception("Giỏ hàng trống.", 400);

            $cartItems = $cart->items()->where('is_selected', true)->with('variant.product')->get();
            if ($cartItems->isEmpty()) throw new Exception("Vui lòng chọn sản phẩm để thanh toán.", 400);

            // 2. Tính toán & Check kho
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $variant = $item->variant;
                // Kiểm tra kho
                if ($variant->stock_qty < $item->quantity) {
                    throw new Exception("Sản phẩm {$variant->product->name} - {$variant->sku} không đủ hàng.", 400);
                }
                $subtotal += $variant->price * $item->quantity;
            }

            $shippingFee = 30000; // Phí ship cố định (có thể nâng cấp tính động sau)
            $total = $subtotal + $shippingFee;

            // 3. Tạo Order
            $order = $this->orderRepo->createOrder([
                'user_id'          => $userId,
                'code'             => 'ORD-' . now()->format('YmdHis') . '-' . rand(1000, 9999),
                'receiver_name'    => $data['receiver_name'],
                'receiver_phone'   => $data['receiver_phone'],
                'shipping_address' => $data['shipping_address'],
                'note'             => $data['note'] ?? null,
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'total'            => $total,
                // Lưu Enum value vào DB
                'payment_method'   => $data['payment_method'], 
                'payment_status'   => PaymentStatus::UNPAID, // Enum: unpaid
                'status'           => OrderStatus::PENDING,  // Enum: pending
            ]);

            // 4. Tạo Order Items & Trừ kho
            foreach ($cartItems as $item) {
                $this->orderRepo->createOrderItem([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $item->variant->product->name,
                    'variant_name'       => $item->variant->sku,
                    'quantity'           => $item->quantity,
                    'unit_price'         => $item->variant->price,
                    'total_price'        => $item->variant->price * $item->quantity,
                ]);

                // Trừ tồn kho
                $item->variant->decrement('stock_qty', $item->quantity);
            }

            // 5. Tạo Payment Record (Trạng thái Pending)
            $this->orderRepo->createPayment([
                'order_id' => $order->id,
                'amount'   => $total,
                'provider' => $data['payment_method'],
                'status'   => PaymentStatus::PENDING, // Enum: pending
            ]);

            // 6. Xóa item đã mua khỏi giỏ
            $cart->items()->where('is_selected', true)->delete();

            return $order;
        });
    }

    public function getMyOrders(int $userId, int $limit)
    {
        return $this->orderRepo->getOrdersByUser($userId, $limit);
    }

    public function getMyOrderDetail(int $userId, string $code)
    {
        $order = $this->orderRepo->getOrderDetail($userId, $code);

        if (!$order) {
            throw new Exception("Đơn hàng không tồn tại hoặc không thuộc về bạn.", 404);
        }

        return $order;
    }

    public function cancelOrder(int $userId, string $code)
    {
        return DB::transaction(function () use ($userId, $code) {
            // 1. Tìm đơn hàng
            $order = $this->orderRepo->findOrderByCode($userId, $code);

            if (!$order) {
                throw new Exception("Đơn hàng không tìm thấy hoặc không thuộc về bạn.", 404);
            }

            // 2. Validate trạng thái
            // Chỉ cho phép hủy khi đơn hàng đang "Chờ xác nhận" (PENDING)
            if ($order->status !== OrderStatus::PENDING) {
                throw new Exception("Không thể hủy đơn hàng này (Đang xử lý hoặc đã hoàn thành).", 400);
            }

            // 3. LOGIC HOÀN KHO (Restore Stock)
            foreach ($order->items as $item) {
                // Kiểm tra nếu variant vẫn tồn tại (chưa bị xóa cứng khỏi DB)
                if ($item->variant) {
                    // Cộng lại số lượng vào kho
                    $item->variant->increment('stock_qty', $item->quantity);
                }
            }

            // 4. Cập nhật trạng thái đơn hàng -> CANCELLED
            $order->status = OrderStatus::CANCELLED;
            $order->save();

            return $order;
        });
    }
}