<?php

namespace App\Repositories\Interfaces\Api;

interface ProductRepositoryInterface
{
    public function getList(array $filters, int $limit);
    public function findDetail(int $id);
}
