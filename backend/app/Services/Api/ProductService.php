<?php

namespace App\Services\Api;

use App\Repositories\Interfaces\Api\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepo
    ) {}

    public function getProducts(array $filters)
    {
        // Gọi thẳng Repo, không cache
        return $this->productRepo->getList($filters);
    }

    public function getProductDetail($id)
    {
        // 1. Lấy chi tiết
        $product = $this->productRepo->findDetail($id);

        // 2. Lấy thêm sản phẩm liên quan
        $related = $this->productRepo->getRelated($product->id, $product->category_id);
        
        // Gán vào object product để Resource xử lý
        $product->related_products = $related;

        return $product;
    }
}