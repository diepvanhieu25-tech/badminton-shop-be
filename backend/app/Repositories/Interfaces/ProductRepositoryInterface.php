<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function getList(array $filters, int $limit);
    public function findDetail(int $id);
}
