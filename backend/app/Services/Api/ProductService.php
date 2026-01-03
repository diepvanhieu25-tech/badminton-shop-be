<?php

namespace App\Services\Api;

use App\Repositories\Interfaces\Api\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Exception;

class ProductService
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getProducts(array $validatedData)
    {
        $limit = $validatedData['limit'] ?? 20;

        return $this->productRepo->getList($validatedData, $limit);
    }

    public function getProductDetail($id)
    {
        // Cache chi tiết sản phẩm trong 1 giờ
        $cacheKey = "product_detail_{$id}";

        return Cache::remember($cacheKey, 3600, function () use ($id) {
            $product = $this->productRepo->findDetail($id);

            if (!$product) {
                throw new Exception("Sản phẩm không tồn tại.");
            }
            return $product;
        });
    }
}
