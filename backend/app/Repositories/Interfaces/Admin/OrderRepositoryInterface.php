<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function paginateOrders(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function findOrderById(int $orderId): ?Order;

}