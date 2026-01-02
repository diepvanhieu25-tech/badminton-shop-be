<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Repositories\Interfaces\Admin\OrderRepositoryInterface; 
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

 
}