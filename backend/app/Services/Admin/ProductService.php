<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Repositories\Interfaces\Admin\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepo
    ) {}

    public function getAll(array $filters)
    {
        return $this->productRepo->paginate($filters);
    }

    public function create(array $data, ?UploadedFile $thumbnail = null, array $gallery = []): Product
    {
        return DB::transaction(function () use ($data, $thumbnail, $gallery) {
            // 1. Upload Thumbnail
            if ($thumbnail) {
                $data['thumbnail'] = $this->uploadFile($thumbnail, 'products/thumbnails');
            }

            // 2. Tạo Product
            $product = $this->productRepo->create($data);

            // 3. Xử lý Variants (Nếu có)
            if (!empty($data['variants']) && $data['has_variants']) {
                $this->productRepo->createVariants($product, $data['variants']);
            }

            // 4. Upload & Tạo Gallery Images
            if (!empty($gallery)) {
                $imagesData = [];
                foreach ($gallery as $index => $file) {
                    $path = $this->uploadFile($file, 'products/gallery');
                    $imagesData[] = [
                        'image_url' => $path,
                        'sort_order' => $index
                    ];
                }
                $this->productRepo->createImages($product, $imagesData);
            }

            return $product;
        });
    }

    public function update(Product $product, array $data, ?UploadedFile $thumbnail = null, array $gallery = []): Product
    {
        return DB::transaction(function () use ($product, $data, $thumbnail, $gallery) {
            // 1. Xử lý Thumbnail mới
            if ($thumbnail) {
                // Xóa ảnh cũ
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $data['thumbnail'] = $this->uploadFile($thumbnail, 'products/thumbnails');
            }

            // 2. Update Product info
            $updatedProduct = $this->productRepo->update($product, $data);

            // 3. Xử lý Variants
            if ($updatedProduct->has_variants && !empty($data['variants'])) {
                $this->productRepo->updateVariants($updatedProduct, $data['variants']);
            }

            // 4. Xử lý Gallery (Append thêm ảnh mới)
            if (!empty($gallery)) {
                $imagesData = [];
                // Tính toán sort order tiếp theo
                $currentMaxSort = $product->images()->max('sort_order') ?? 0;
                
                foreach ($gallery as $index => $file) {
                    $path = $this->uploadFile($file, 'products/gallery');
                    $imagesData[] = [
                        'image_url' => $path,
                        'sort_order' => $currentMaxSort + $index + 1
                    ];
                }
                $this->productRepo->createImages($updatedProduct, $imagesData);
            }

            return $updatedProduct;
        });
    }

    public function delete(Product $product): bool
    {
        // Soft delete nên không cần xóa file vật lý ngay
        return DB::transaction(function () use ($product) {
            // Xóa variants
            $product->variants()->delete();
            // Xóa product
            return $this->productRepo->delete($product);
        });
    }

    // Helper upload
    private function uploadFile(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }
}