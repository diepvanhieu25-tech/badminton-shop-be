<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
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
        $product = $this->productRepo->findDetail($id);

        if (!$product) {
            throw new Exception("Sản phẩm không tồn tại.");
        }

        return $product;
    }
}
