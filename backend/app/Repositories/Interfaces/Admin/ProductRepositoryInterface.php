<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function find(int $id): ?Product;
    public function create(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): bool;
    
    // Xử lý quan hệ
    public function createVariants(Product $product, array $variants): void;
    public function updateVariants(Product $product, array $variants): void;
    public function createImages(Product $product, array $imagesData): void;
}