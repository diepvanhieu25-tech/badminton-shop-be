<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'product_name' => $this->product_name, // Tên lưu cứng lúc mua
            'variant_name' => $this->variant_name, // SKU hoặc tên biến thể
            'quantity'     => (int) $this->quantity,
            'unit_price'   => (float) $this->unit_price,
            'total_price'  => (float) $this->total_price,
            
            // Lấy ảnh: Cố gắng lấy từ variant hiện tại, nếu variant bị xóa thì lấy null hoặc ảnh mặc định
            'image_url'    => $this->variant && $this->variant->image 
                                ? $this->variant->image 
                                : null, // Hoặc $this->variant->product->thumbnail
        ];
    }
}