<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Logic xử lý URL ảnh chuẩn xác
        $thumb = null;
        if ($this->thumbnail) {
            $thumb = filter_var($this->thumbnail, FILTER_VALIDATE_URL) 
                ? $this->thumbnail 
                : asset(Storage::url($this->thumbnail));
        }

        // Tính % giảm giá để FE hiển thị badge (Ví dụ: -20%)
        $discount = 0;
        if ($this->original_price > $this->price) {
            $discount = round((($this->original_price - $this->price) / $this->original_price) * 100);
        }

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'sku'            => $this->sku,
            'thumbnail'      => $thumb,
            'price'          => (float) $this->price,
            'original_price' => (float) $this->original_price,
            'discount_rate'  => $discount,
            'is_featured'    => (boolean) $this->is_featured,
            
            // Trả về tên Category/Brand để hiển thị luôn
            'category_name'  => $this->category->name ?? '',
            'brand_name'     => $this->brand->name ?? '',
        ];
    }
}