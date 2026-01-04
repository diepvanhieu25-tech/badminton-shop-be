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
            // Trường hợp 1: Link tuyệt đối (Google/Facebook/Link ảnh ngoài)
            if (filter_var($this->avatar_url, FILTER_VALIDATE_URL)) {
                $avatar = $this->avatar_url;
            } 
            // Trường hợp 2: File local (Upload lên host)
            else {
                // Giả sử DB lưu tên file "abc.jpg", code sẽ nối thành "avatars/abc.jpg"
                $avatar = asset(Storage::url($this->avatar_url));
            }
        }

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'role'       => $this->role, 
            'status'  => $this->status, 
            'avatar_url' => $avatar,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}