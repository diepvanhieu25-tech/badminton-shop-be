<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Repositories\Interfaces\Admin\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Product::query()
            ->with([
                'brand:id,name',
                'category:id,name',
                'variants:id,product_id,price,stock_qty',
                'images:id,product_id,image_url,sort_order',
            ])
            ->when($filters['q'] ?? null, function ($q, $s) {
                $s = trim($s);
                $q->where(fn ($sub) =>
                    $sub->where('name', 'like', "%$s%")
                        ->orWhere('sku', 'like', "%$s%")
                );
            })
            ->when($filters['status'] ?? null, fn ($q, $st) => $q->where('status', $st))
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findById(int $id): Product
    {
        return Product::with([
            'brand:id,name',
            'category:id,name',
            'variants',
            'images' => fn ($q) => $q->orderBy('sort_order'),
        ])->findOrFail($id);
    }

    /* =========================
     * CREATE
     * ========================= */

    public function create(array $data): Product
    {
        [$variants, $images] = $this->extractNestedData($data);

        $data['thumbnail'] = $this->storeThumbnail($data['thumbnail'] ?? null);

        $product = Product::create($data);

        $this->syncVariants($product, $variants);
        $this->storeImages($product, $images);

        return $this->findById($product->id);
    }

    /* =========================
     * UPDATE
     * ========================= */

    public function update(Product $product, array $data): Product
    {
        [$variants, $newImages, $existingImages] = $this->extractNestedData($data, true);

        if (!empty($data['thumbnail'])) {
            $data['thumbnail'] = $this->storeThumbnail($data['thumbnail']);
        } else {
            unset($data['thumbnail']);
        }

        $product->update($data);

        if (array_key_exists('status', $data)) {
            $product->status = $data['status'];
            $product->save();
        }

        $this->syncVariants($product, $variants);
        $this->syncExistingImages($product, $existingImages);
        $this->storeImages($product, $newImages);

        return $this->findById($product->id);
    }

    /* =========================
     * DELETE
     * ========================= */

    public function delete(Product $product): void
    {
        $product->delete();
    }

    /* =========================
     * VARIANTS
     * ========================= */

    private function syncVariants(Product $product, array $variants): void
    {
        foreach ($variants as $v) {
            $id = $v['id'] ?? null;
            $delete = !empty($v['_delete']);

            if ($id) {
                $variant = ProductVariant::where('product_id', $product->id)
                    ->where('id', $id)
                    ->first();

                if (!$variant) continue;

                if ($delete) {
                    $variant->delete();
                    continue;
                }

                $variant->update($this->mapVariantData($product, $v));
            } else {
                if ($delete) continue;

                ProductVariant::create(
                    $this->mapVariantData($product, $v)
                );
            }
        }
    }

    private function mapVariantData(Product $product, array $v): array
    {
        return [
            'product_id'     => $product->id,
            'sku'            => $v['sku'] ?? null,
            'attributes'     => $this->decodeJson($v['attributes_json'] ?? null),
            'price'          => $v['price'] ?? 0,
            'original_price' => $v['original_price'] ?? 0,
            'stock_qty'      => $v['stock_qty'] ?? 0,
            'stock_alert'    => $v['stock_alert'] ?? 0,
        ];
    }

    /* =========================
     * IMAGES
     * ========================= */

    private function storeImages(Product $product, array $files): void
    {
        $startOrder = (int) ($product->images()->max('sort_order') ?? 0);

        foreach ($files as $i => $file) {
            if (!$file instanceof UploadedFile) continue;

            $path = $file->store('uploads/products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => $path,
                'sort_order' => $startOrder + $i + 1,
            ]);
        }
    }

    private function syncExistingImages(Product $product, array $images): void
    {
        foreach ($images as $img) {
            $image = ProductImage::where('product_id', $product->id)
                ->where('id', $img['id'])
                ->first();

            if (!$image) continue;

            if (!empty($img['_delete'])) {
                $image->delete();
                continue;
            }

            // Không cho user nhập → giữ nguyên sort
        }
    }

    /* =========================
     * HELPERS
     * ========================= */

    private function storeThumbnail(?UploadedFile $file): ?string
    {
        if (!$file) return null;
        return $file->store('uploads/products', 'public');
    }

    private function extractNestedData(array &$data, bool $isUpdate = false): array
    {
        $variants = $data['variants'] ?? [];
        $images   = $data['images'] ?? [];

        $existingImages = $isUpdate ? ($data['existing_images'] ?? []) : [];

        unset(
            $data['variants'],
            $data['images'],
            $data['existing_images']
        );

        return $isUpdate
            ? [$variants, $images, $existingImages]
            : [$variants, $images];
    }

    private function decodeJson(?string $json): array
    {
        if (!$json) return [];
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }
}
