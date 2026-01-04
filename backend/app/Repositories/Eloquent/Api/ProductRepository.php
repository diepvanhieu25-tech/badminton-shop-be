<?php

namespace App\Repositories\Eloquent\Api;

use App\Models\Product;
use App\Repositories\Interfaces\Api\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    // 1. Lấy danh sách & Lọc
    public function getList(array $filters)
    {
        $limit = $filters['limit'] ?? 20;

        // Select tối ưu cột cần thiết
        $query = Product::select([
            'id', 'name', 'sku', 'thumbnail', 'price', 
            'original_price', 'category_id', 'brand_id', 'has_variants', 'is_featured'
        ])->where('status', 'active'); // Chỉ lấy SP đang active

        // --- FILTER ---
        if (!empty($filters['keyword'])) {
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }
        if (!empty($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // --- SORT ---
        $sortBy = $filters['sort_by'] ?? 'newest';
        switch ($sortBy) {
            case 'price_asc':  $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'name_asc':   $query->orderBy('name', 'asc'); break;
            default:           $query->orderBy('created_at', 'desc'); break;
        }

        // Eager load category/brand để lấy tên hiển thị
        return $query->with(['category:id,name', 'brand:id,name'])->paginate($limit);
    }

    // 2. Lấy chi tiết đầy đủ
    public function findDetail(int $id)
    {
        return Product::where('status', 'active')
            ->with([
                'category:id,name',
                'brand:id,name',
                'images:id,product_id,image_url', // Album ảnh
                'variants'                         // Biến thể (Size/Màu)
            ])
            ->findOrFail($id); // Tự báo lỗi 404 nếu không thấy
    }

    // 3. Lấy sản phẩm liên quan
    public function getRelated(int $currentId, int $categoryId, int $limit = 4)
    {
        return Product::select(['id', 'name', 'thumbnail', 'price', 'original_price', 'category_id'])
            ->where('status', 'active')
            ->where('category_id', $categoryId)
            ->where('id', '!=', $currentId) // Trừ chính nó ra
            ->inRandomOrder()               // Lấy ngẫu nhiên
            ->limit($limit)
            ->get();
    }
}