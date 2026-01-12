<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Order;
use App\Models\Shipment;
use App\Repositories\Interfaces\Admin\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator; 

class OrderRepository implements OrderRepositoryInterface
{
    public function paginateOrders(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Order::query()
            ->with(['user', 'items', 'payment']);

        if (!empty($filters['q'])) {
            $search = trim($filters['q']);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sort = $filters['sort'] ?? 'date_desc';

        match ($sort) {
            'date_asc'   => $query->orderBy('created_at', 'asc'),
            'total_asc'  => $query->orderBy('total', 'asc'),
            'total_desc' => $query->orderBy('total', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        return $query->paginate($perPage);
    }

    public function findOrderById(int $orderId): ?Order
    {
        return Order::with(['items.variant.product', 'user', 'payment', 'shipment'])
            ->findOrFail($orderId);
    }

    public function updateStatus(Order $order, string $status): bool
    {
        return $order->update(['status' => $status]);
    }

    public function createShipment(Order $order, array $data): Shipment
    {
        // Data đã được validate và chuẩn bị ở Service
        return Shipment::create([
            'order_id'      => $order->id,
            'carrier'       => $data['carrier'],
            'tracking_code' => $data['tracking_code'],
            'note'          => $data['note'] ?? null,
            'cod_amount'    => $data['cod_amount'] ?? 0,
            'status'        => 'shipping', // Trạng thái của riêng shipment
            'shipped_at'    => now(),
        ]);
    }
}