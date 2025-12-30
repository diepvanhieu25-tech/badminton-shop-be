<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Tính tổng tiền cả giỏ hàng
        $totalPrice = $this->items->sum(function ($item) {
            return $item->variant ? ($item->variant->price * $item->quantity) : 0;
        });

        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'total_items' => $this->items->count(), // Tổng số dòng sản phẩm
            'total_qty'   => $this->items->sum('quantity'), // Tổng số lượng sản phẩm
            'total_price' => (float) $totalPrice, // Tổng tiền tạm tính

            // Danh sách items
            'items'       => CartItemResource::collection($this->items),
        ];
    }
}
