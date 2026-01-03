<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Tính tổng tiền realtime
        $totalPrice = $this->items->sum(function ($item) {
            return $item->variant ? ($item->variant->price * $item->quantity) : 0;
        });

        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'total_items' => $this->items->count(),          // Số dòng sản phẩm
            'total_qty'   => (int) $this->items->sum('quantity'), // Tổng số lượng
            'total_price' => (float) $totalPrice,            // Tổng tiền tạm tính
            
            // Danh sách chi tiết
            'items'       => CartItemResource::collection($this->items),
        ];
    }
}