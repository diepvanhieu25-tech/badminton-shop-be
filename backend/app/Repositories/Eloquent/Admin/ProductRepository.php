<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Repositories\Interfaces\Admin\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Product::query()->with(['category', 'brand']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('sku', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->orderByDesc('id')->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return Product::with(['variants', 'images'])->findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    // === Xử lý Variants ===
    public function createVariants(Product $product, array $variants): void
    {
        foreach ($variants as $variantData) {
            $product->variants()->create($variantData);
        }
    }

    public function updateVariants(Product $product, array $variants): void
    {
        // 1. Lấy danh sách ID variant hiện tại trong DB
        $currentVariantIds = $product->variants()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($variants as $data) {
            if (isset($data['id']) && $data['id']) {
                // Update
                $submittedIds[] = $data['id'];
                ProductVariant::where('id', $data['id'])->update($data);
            } else {
                // Create new trong lúc update
                $product->variants()->create($data);
            }
        }

        // 2. Xóa các variant không còn tồn tại trong request
        $idsToDelete = array_diff($currentVariantIds, $submittedIds);
        if (!empty($idsToDelete)) {
            ProductVariant::destroy($idsToDelete);
        }
    }

    // === Xử lý Images ===
    public function createImages(Product $product, array $imagesData): void
    {
        // $imagesData = [['image_url' => 'path/a.jpg', 'sort_order' => 0], ...]
        foreach ($imagesData as $img) {
            $product->images()->create($img);
        }
    }
}