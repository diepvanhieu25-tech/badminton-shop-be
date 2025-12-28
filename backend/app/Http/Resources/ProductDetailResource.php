<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'sku'            => $this->sku,
            'description'    => $this->description,
            'thumbnail'      => $this->thumbnail ? asset($this->thumbnail) : null,
            'price'          => (float) $this->price,
            'original_price' => (float) $this->original_price,
            'category'       => new CategoryResource($this->whenLoaded('category')),
            'brand'          => new BrandResource($this->whenLoaded('brand')),

            // Album ảnh
            'gallery'        => $this->images->map(function ($img) {
                return ['id' => $img->id, 'url' => asset($img->image_url)];
            }),

            // Danh sách biến thể
            'variants'       => ProductVariantResource::collection($this->whenLoaded('variants')),
        ];
    }
}
