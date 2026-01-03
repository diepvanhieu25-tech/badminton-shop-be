<?php
namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // 1. Kiểm tra kỹ xem variant còn tồn tại không
        $variant = $this->variant; 
        $product = $variant ? $variant->product : null;

        // Nếu variant bị xóa (null), ta trả về dữ liệu default để không crash API
        if (!$variant || !$product) {
            return [
                'id' => $this->id,
                'error' => 'Sản phẩm này không còn tồn tại',
                'is_invalid' => true, // Cờ để FE biết mà ẩn hoặc xóa dòng này
            ];
        }

        return [
            'id'                 => $this->id,
            'quantity'           => (int) $this->quantity,
            'product_variant_id' => $this->product_variant_id,
            
            // Dùng toán tử null safe (?->) hoặc default value
            'sku'        => $variant->sku ?? 'N/A',
            'name'       => $product->name ?? 'Sản phẩm lỗi',
            'attributes' => $variant->attributes, 
            
            'image_url'  => $this->getImageUrl($variant, $product),

            'price'      => (float) ($variant->price ?? 0),
            'subtotal'   => (float) (($variant->price ?? 0) * $this->quantity),

            'in_stock'   => $variant->stock_qty >= $this->quantity,
            'max_qty'    => $variant->stock_qty ?? 0,
            'is_selected' => (boolean) $this->is_selected,
        ];
    }

    private function getImageUrl($variant, $product)
    {
        // Check null kỹ ở đây nữa
        $path = $variant?->image ?? $product?->thumbnail;
        return $path ? (filter_var($path, FILTER_VALIDATE_URL) ? $path : Storage::url($path)) : null;
    }
}