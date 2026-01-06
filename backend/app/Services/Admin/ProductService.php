<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Repositories\Interfaces\Admin\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $repo
    ) {}
    
    public function list(array $filters)
    {
        return $this->repo->paginate($filters);
    }

    public function detail(int $id): Product
    {
        return $this->repo->findById($id);
    }

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            return $this->repo->update($product, $data);
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $this->repo->delete($product);
        });
    }
}
