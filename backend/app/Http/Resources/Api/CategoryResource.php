<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $image = null;
        if ($this->image_url) {
            if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
                $image = $this->image_url;
            } else {
                $image = asset(Storage::url($this->image_url));
            }
        }

        return [
            'id'        => $this->id,
            'name'      => $this->name,

            'image_url' => $image,
            'parent_id' => $this->parent_id,

            'children'  => CategoryResource::collection($this->whenLoaded('childrenRecursive')),
        ];
    }
}
