<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'sku'            => $this->sku,
            'thumbnail'      => $this->thumbnail ? Storage::url($this->thumbnail) : null,
            'price'          => (float) $this->price,
            'original_price' => (float) $this->original_price,
            'description'    => $this->description,
            
            // Relation đơn giản
            'category'       => [
                'id'   => $this->category->id ?? null,
                'name' => $this->category->name ?? null,
            ],
            'brand'          => [
                'id'   => $this->brand->id ?? null,
                'name' => $this->brand->name ?? null,
            ],

            // Album ảnh (Map lại URL)
            'images'         => $this->images->map(function ($img) {
                return [
                    'id'  => $img->id,
                    'url' => Storage::url($img->image_url),
                ];
            }),

            // Biến thể (Variants)
            'variants'       => $this->variants->map(function ($variant) {
                return [
                    'id'         => $variant->id,
                    'sku'        => $variant->sku,
                    'attributes' => $variant->attributes, // JSON đã được cast thành Array ở Model
                    'price'      => (float) $variant->price,
                    'stock_qty'  => $variant->stock_qty,
                    'image'      => $variant->image ? Storage::url($variant->image) : null,
                ];
            }),
        ];
    }
}