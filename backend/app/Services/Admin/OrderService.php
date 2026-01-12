<?php

namespace App\Services\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
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

        $this->repo->updateStatus($order, $status);
        
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