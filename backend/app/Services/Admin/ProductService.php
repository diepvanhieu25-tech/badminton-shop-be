<?php

namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductService
{
    /**
     * Lấy danh sách sản phẩm (Có tìm kiếm + Phân trang)
     * Đây là hàm bị thiếu gây ra lỗi của bạn
     */
    public function list(array $filters = [], int $perPage = 10)
    {
        // Eager load category và brand để tránh lỗi N+1 query
        $query = Product::with(['category', 'brand']); 

        // Lọc theo tên hoặc SKU
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sku', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Lọc theo danh mục
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        
        // Lọc theo trạng thái
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Sắp xếp mới nhất và phân trang
        return $query->latest()->paginate($perPage);
    }

    /**
     * Tạo mới sản phẩm
     */
    public function create(array $data)
    {
        // Xử lý upload ảnh
        if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
            $data['thumbnail'] = $data['thumbnail']->store('products', 'public');
        }

        return Product::create($data);
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Product $product, array $data)
    {
        // Xử lý ảnh mới
        if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
            // Xóa ảnh cũ nếu có
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            
            $data['thumbnail'] = $data['thumbnail']->store('products', 'public');
        }

        $product->update($data);
        return $product;
    }

    /**
     * Xóa sản phẩm
     */
    public function delete(Product $product)
    {
        // Nếu xóa mềm (Soft Delete) thì không cần xóa file ảnh ngay
        // Nếu muốn xóa sạch ảnh thì thêm logic xóa file ở đây
        return $product->delete();
    }
}