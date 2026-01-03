<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar_url' => $this->avatar_url
                ? url('/storage/' . $this->avatar_url)
                : null,

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
