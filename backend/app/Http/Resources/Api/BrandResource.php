<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BrandResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'logo_url' => $this->logo_url ? Storage::url($this->logo_url) : null,
        ];
    }
}
