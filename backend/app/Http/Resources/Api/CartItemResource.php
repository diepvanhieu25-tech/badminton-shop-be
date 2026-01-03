<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // $this ở đây là CartItem Model
        $variant = $this->variant;
        $product = $variant ? $variant->product : null;

        return [
            'id'                 => $this->id,
            'quantity'           => (int) $this->quantity,
            'product_variant_id' => $this->product_variant_id,
            
            // Thông tin hiển thị (Flatten dữ liệu cho FE dễ lấy)
            'sku'           => $variant?->sku,
            'name'          => $product?->name ?? 'Sản phẩm không tồn tại',
            'attributes'    => $variant?->attributes, // Ví dụ: {"color": "Đỏ", "size": "M"}
            
            // Xử lý ảnh: Ưu tiên ảnh của Variant, nếu không có lấy ảnh Product
            'image_url'     => $this->getImageUrl($variant, $product),

            // Tính toán tiền
            'price'         => (float) ($variant?->price ?? 0),
            'subtotal'      => (float) ($variant?->price * $this->quantity),

            // Kiểm tra tồn kho (Để FE hiện thông báo nếu hết hàng)
            'in_stock'      => $variant && $variant->stock_qty >= $this->quantity,
            'max_qty'       => $variant?->stock_qty ?? 0,
        ];
    }

    private function getImageUrl($variant, $product)
    {
        $path = $variant?->image ?? $product?->thumbnail;
        return $path ? Storage::url($path) : null;
    }
}