<?php

namespace App\Services\Api;

use App\Repositories\Interfaces\Api\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function updateProfile($user, array $data, ?UploadedFile $avatar = null)
    {
        // 1. Xử lý Avatar
        if ($avatar) {
            // Xóa ảnh cũ: Chỉ xóa nếu nó KHÔNG phải là link ngoại (http...) và file có tồn tại
            if ($user->avatar_url && !filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($user->avatar_url)) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
            }

            // Tạo tên file
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
            $path = 'avatars/' . $filename;

            // Resize & Encode ảnh (Intervention v3)
            $manager = new ImageManager(new Driver());
            $image = $manager->read($avatar);
            
            // Resize (scale width 300, auto height)
            $image->scale(width: 300);
            
            // Encode
            $encodedImage = $image->toJpeg(quality: 80);

            // Lưu file
            Storage::disk('public')->put($path, $encodedImage);

            // Gán đường dẫn lưu DB
            $data['avatar_url'] = $path;
        }

        // 2. Xử lý Password (HASH)
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['email']);

        // 3. Update DB
        return $this->userRepository->update($user, $data)->fresh();
    }
}