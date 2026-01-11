<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function paginateOrders(array $filters = [], int $perPage = 20): LengthAwarePaginator;
    public function findOrderById(int $orderId): ?Order;
    public function updateStatus(Order $order, string $status): bool;
    public function createShipment(Order $order, array $data): Shipment;
}