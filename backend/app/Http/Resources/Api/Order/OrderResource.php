<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'code'             => $this->code,
            'receiver_info'    => [
                'name'    => $this->receiver_name,
                'phone'   => $this->receiver_phone,
                'address' => $this->shipping_address,
            ],
            'financial'        => [
                'subtotal'     => (float) $this->subtotal,
                'shipping_fee' => (float) $this->shipping_fee,
                'total'        => (float) $this->total,
            ],
            'status'           => [
                'key'   => $this->status->value,       // 'pending'
                'label' => $this->status->label(),     // 'Chờ xác nhận'
                'color' => $this->status->color(),     // 'warning'
            ],
            'payment'          => [
                'method' => $this->payment_method->label(), // 'Ví VNPay'
                'status' => $this->payment_status->value,   // 'unpaid'
            ],
            'created_at'       => $this->created_at->format('d/m/Y H:i'),
            'items'         => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}