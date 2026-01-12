<?php

namespace App\Services\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Enums\PaymentStatus;
use App\Repositories\Interfaces\Admin\OrderRepositoryInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $repo
    ) {}

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->repo->paginateOrders($filters, 10);
    }

    public function detail(int $id): Order
    {
        return $this->repo->findOrderById($id);
    }

    public function updateStatus(int $orderId, string $status): Order
    {
        $order = $this->repo->findOrderById($orderId);

        if ($order->status === OrderStatus::CANCELLED) {
            throw new Exception('Không thể cập nhật đơn hàng đã hủy.');
        }
        
        DB::transaction(function () use ($order, $status) {
            // 1. Cập nhật trạng thái đơn hàng (Logic cũ)
            $this->repo->updateStatus($order, $status);

            // 2. Tự động cập nhật thanh toán
            // Nếu trạng thái mới là COMPLETED (Hoàn thành) -> Set Payment Status thành PAID
            if ($status === OrderStatus::COMPLETED->value) {
                // Kiểm tra nếu chưa thanh toán thì mới update để tránh ghi đè ngày thanh toán cũ (nếu có)
                if ($order->payment_status !== PaymentStatus::PAID) {
                    $order->update([
                        'payment_status' => PaymentStatus::PAID
                        // Nếu database có cột 'paid_at', bạn có thể thêm: 'paid_at' => now()
                    ]);
                }
            }
        });

        return $order;
    }

    public function shipOrder(int $orderId, array $data): Order
    {
        $order = $this->repo->findOrderById($orderId);

        DB::transaction(function () use ($order, $data) {
            
            $codAmount = ($order->payment_status !== 'paid') ? $order->total : 0;
            
            $shipmentData = array_merge($data, [
                'cod_amount' => $codAmount
            ]);

            $this->repo->createShipment($order, $shipmentData);

            $this->repo->updateStatus($order, OrderStatus::SHIPPING->value);
        });

        return $order;
    }
}