<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'image_url' => $this->image_url ? asset($this->image_url) : null,
            'parent_id' => $this->parent_id,

            //Đệ quy children
            'children'  => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
