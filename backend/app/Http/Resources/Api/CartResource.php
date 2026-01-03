<?php
namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // TÍNH TOÁN LẠI: Chỉ tính tiền những món được chọn (is_selected = true)
        $selectedItems = $this->items->where('is_selected', true);

        $totalPrice = $selectedItems->sum(function ($item) {
            return $item->variant ? ($item->variant->price * $item->quantity) : 0;
        });

        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'total_items' => $this->items->count(),
            'total_qty'   => (int) $this->items->sum('quantity'),
            
            // Tổng tiền thanh toán (Chỉ tính các món đã chọn)
            'total_price' => (float) $totalPrice, 
            
            'items'       => CartItemResource::collection($this->items),
        ];
    }
}