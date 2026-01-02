<?php

namespace App\Repositories\Eloquent\Api;

use App\Models\Product;
use App\Repositories\Interfaces\Api\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getList(array $filters, int $limit)
    {
        $query = Product::select([
            'id',
            'name',
            'sku',
            'thumbnail',
            'price',
            'original_price',
            'category_id',
            'brand_id',
        ])
            ->where('status', 'active');

        // 1. Lọc theo danh mục
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // 2. Lọc theo thương hiệu
        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        // 3. Tìm kiếm theo tên
        if (!empty($filters['keyword'])) {
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        // 4. Lọc theo giá
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // 5. Sắp xếp
        $sortBy = $filters['sort_by'] ?? 'newest';
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Trả về kết quả phân trang
        return $query->with([
            'category:id,name',
            'brand:id,name'
        ])->paginate($limit);
    }

    public function findDetail(int $id)
    {
        return Product::where('status', 'active')
            ->with([
                'category:id,name',
                'brand:id,name',
                'images:id,product_id,image_url', // Chỉ lấy cột cần thiết của ảnh
                'variants' // Variants thì lấy hết để FE xử lý logic
            ])
            ->find($id);
    }
}
