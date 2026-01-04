<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        $avatar = null;
        if ($this->avatar_url) {
            // Nếu là link tuyệt đối (Google/Facebook) -> Giữ nguyên
            if (filter_var($this->avatar_url, FILTER_VALIDATE_URL)) {
                $avatar = $this->avatar_url;
            } else {
                // Nếu là file local -> Thêm đường dẫn Storage
                $avatar = asset(Storage::url('/avatars' . $this->avatar_url));
            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar_url' => $avatar,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
