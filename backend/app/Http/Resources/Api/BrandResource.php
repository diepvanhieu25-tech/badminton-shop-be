<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $logo = null;

        if ($this->logo_url) {
            if (filter_var($this->logo_url, FILTER_VALIDATE_URL)) {
                $logo = $this->logo_url;
            } else {
                $logo = asset(Storage::url($this->logo_url));
            }
        }

        return [
            'id'    => $this->id,
            'name'    => $this->name,
            'logo'     => $logo,
        ];
    }
}
