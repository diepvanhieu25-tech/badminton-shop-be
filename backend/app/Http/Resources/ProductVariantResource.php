<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'sku'            => $this->sku,
            // Attributes là JSON trong DB, Model đã cast sang Array
            'attributes'     => $this->attributes, // VD: {"color": "Red", "size": "M"}
            'price'          => (float) $this->price,
            'original_price' => (float) $this->original_price,
            'stock_qty'      => (int) $this->stock_qty,
            'image'          => $this->image ? asset($this->image) : null,
        ];
    }
}
