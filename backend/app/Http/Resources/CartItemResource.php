<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Kiểm tra an toàn: Nếu variant hoặc product bị xóa thì trả về null hoặc xử lý riêng
        $variant = $this->variant;
        $product = $variant ? $variant->product : null;

        return [
            'id'                 => $this->id,
            'product_variant_id' => $this->product_variant_id,
            'quantity'           => (int) $this->quantity,

            // Thông tin chi tiết sản phẩm để hiển thị
            'product_name'       => $product ? $product->name : 'Sản phẩm không tồn tại',
            'sku'                => $variant ? $variant->sku : null,
            'thumbnail'          => ($product && $product->thumbnail) ? asset($product->thumbnail) : null,

            // Attributes (Size/Màu)
            'attributes'         => $variant ? $variant->attributes : [],

            // Giá tại thời điểm hiện tại (Lấy từ bảng Variant)
            'price'              => $variant ? (float) $variant->price : 0,

            // Thành tiền (Số lượng * Giá)
            'subtotal'           => $variant ? (float) $variant->price * $this->quantity : 0,

            // Cảnh báo tồn kho (Optional: để FE biết mà hiển thị "Hết hàng")
            'in_stock'           => $variant ? ($variant->stock_qty >= $this->quantity) : false,
        ];
    }
}
