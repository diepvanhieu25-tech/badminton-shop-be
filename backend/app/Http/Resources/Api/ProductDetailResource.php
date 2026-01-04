<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Import Str để tạo slug

class ProductDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Helper nhỏ để xử lý URL
        $getUrl = fn($path) => ($path && !filter_var($path, FILTER_VALIDATE_URL)) ? asset(Storage::url($path)) : $path;

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'sku'            => $this->sku,
            'description'    => $this->description,
            'thumbnail'      => $getUrl($this->thumbnail),
            'price'          => (float) $this->price,
            'original_price' => (float) $this->original_price,
            
            'category'       => ['id' => $this->category->id ?? null, 'name' => $this->category->name ?? null],
            'brand'          => ['id' => $this->brand->id ?? null, 'name' => $this->brand->name ?? null],

            // 1. Album ảnh
            'images'         => $this->images->map(fn($img) => [
                'id'  => $img->id,
                'url' => $getUrl($img->image_url)
            ]),

            // 2. Biến thể (Đã xử lý attributes chuẩn)
            'variants'       => $this->variants->map(fn($v) => [
                'id'         => $v->id,
                'sku'        => $v->sku,
                
                // --- GỌI HÀM XỬ LÝ ATTRIBUTES TẠI ĐÂY ---
                'attributes' => $this->parseAttributes($v->attributes),
                
                'price'      => (float) $v->price,
                'stock_qty'  => (int) $v->stock_qty,
                'image'      => $getUrl($v->image),
                'is_in_stock'=> $v->stock_qty > 0,
            ]),

            'related_products' => ProductResource::collection($this->when(isset($this->related_products), $this->related_products)),
        ];
    }

    /**
     * Hàm biến đổi Attributes JSON thành Mảng chuẩn cho FE
     * Input: {"Cán": "G5", "Trọng lượng": "4U"}
     */
    protected function parseAttributes($attributes)
    {
        // Nếu null hoặc rỗng trả về mảng rỗng
        if (empty($attributes) || !is_array($attributes)) {
            return [];
        }

        $result = [];

        foreach ($attributes as $key => $value) {
            // Logic:
            // - name: Giữ nguyên tên gốc để hiển thị (VD: "Cán", "Size")
            // - code: Tạo slug để FE code logic icon (VD: "Cán" -> "can", "Size" -> "size")
            
            $result[] = [
                'code'  => Str::slug($key), // Key dùng cho logic (viết thường, không dấu)
                'name'  => ucfirst($key),   // Tên hiển thị ra màn hình
                'value' => $value           // Giá trị (G5, 4U, 39...)
            ];
        }

        return $result;
    }
}