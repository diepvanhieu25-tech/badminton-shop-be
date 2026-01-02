<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'sku'            => $this->sku,
            'thumbnail'      => $this->thumbnail ? asset($this->thumbnail) : null,
            'price'          => (float) $this->price,
            'original_price' => (float) $this->original_price,
            'category_name'  => $this->category->name ?? null,
            'brand_name'     => $this->brand->name ?? null,
        ];
    }
}
