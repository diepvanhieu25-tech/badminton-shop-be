<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Helper xử lý url (local / s3 / full url)
        $getUrl = fn ($path) =>
            ($path && !filter_var($path, FILTER_VALIDATE_URL))
                ? asset(Storage::url($path))
                : $path;

        $imagePath = null;

        if ($this->variant?->image) {
            $imagePath = $this->variant->image;
        } elseif ($this->variant?->product?->thumbnail) {
            $imagePath = $this->variant->product->thumbnail;
        }

        return [
            'id'           => $this->id,
            'product_name' => $this->product_name,
            'variant_name' => $this->variant_name,
            'quantity'     => (int) $this->quantity,
            'unit_price'   => (float) $this->unit_price,
            'total_price'  => (float) $this->total_price,

            // URL chuẩn cho FE
            'image_url'    => $getUrl($imagePath),
        ];
    }
}
