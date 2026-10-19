<?php

namespace App\Repositories\Eloquent\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Repositories\Interfaces\Api\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    public function createOrderItem(array $data)
    {
        return OrderItem::create($data);
    }

    public function createPayment(array $data)
    {
        return Payment::create($data);
    }

    public function getOrdersByUser(int $userId, int $limit = 10)
    {
        return Order::where('user_id', $userId)
            ->orderByDesc('id') // Mới nhất lên đầu
            ->paginate($limit);
    }

    public function getOrderDetail(int $userId, string $code)
    {
        return Order::where('user_id', $userId)
            ->where('code', $code)
            // Eager Load: Lấy luôn Items và Variant để API Resource dùng
            ->with(['items.variant'])
            ->first();
    }

    public function findOrderByCode(int $userId, string $code)
    {
        return Order::where('user_id', $userId)
            ->where('code', $code)
            // Load items và variant để phục vụ việc hoàn kho trong Service
            ->with(['items.variant'])
            ->first();
    }

    public function findByCode(string $code)
    {
        return Order::where('code', $code)->first();
    }

    public function updatePaymentStatus(string $orderCode, array $paymentData, string $status)
    {
        // Tìm đơn hàng
        $order = Order::where('code', $orderCode)->first();
        if (!$order) return null;

        $isSuccess = ($status === PaymentStatus::SUCCESS->value || $status === PaymentStatus::PAID->value || $status === 'paid' || $status === 'success');

        // Cập nhật Order & Payment
        if ($isSuccess) {
            $order->update([
                'payment_status' => PaymentStatus::PAID,
                'status'         => OrderStatus::PROCESSING, // Đã trả tiền thì chuyển sang xử lý luôn
            ]);

            $payment = $order->payment ?? $order->payment()->first();
            if (!$payment) {
                $payment = $order->payment()->create([
                    'amount'         => $paymentData['amount'] ?? $order->total,
                    'provider'       => $order->payment_method ?? 'vnpay',
                    'status'         => PaymentStatus::SUCCESS,
                    'paid_at'        => now(),
                    'transaction_id' => $paymentData['transaction_code'] ?? ('VNP-' . time()),
                ]);
            } else {
                $payment->update([
                    'status'         => PaymentStatus::SUCCESS,
                    'paid_at'        => now(),
                    'transaction_id' => $paymentData['transaction_code'] ?? $payment->transaction_id ?? ('VNP-' . time()),
                ]);
            }
        } else {
            $order->update([
                'payment_status' => PaymentStatus::FAILED,
            ]);

            $payment = $order->payment ?? $order->payment()->first();
            if ($payment) {
                $payment->update([
                    'status' => PaymentStatus::FAILED,
                ]);
            }
        }

        // Lưu lịch sử giao dịch nếu có quan hệ transactions
        try {
            $payment = $order->payment ?? $order->payment()->first();
            if ($payment && method_exists($payment, 'transactions')) {
                $payment->transactions()->create([
                    'transaction_code' => $paymentData['transaction_code'] ?? null,
                    'status'           => $status,
                    'amount'           => $paymentData['amount'] ?? $order->total,
                    'raw_data'         => $paymentData['raw_data'] ?? [],
                ]);
            }
        } catch (\Throwable $t) {
            \Illuminate\Support\Facades\Log::warning('Không thể lưu transaction log: ' . $t->getMessage());
        }

        return $order;
    }
}
