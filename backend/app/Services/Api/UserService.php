<?php

namespace App\Services\Api;

use App\Repositories\Interfaces\Api\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
// Import thư viện ảnh (Dùng cho Intervention Image v3)
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function updateProfile($user, array $data, ?UploadedFile $avatar = null)
    {
        if ($avatar) {
            // 1. Xóa ảnh cũ nếu có
            if ($user->avatar_url) {
                // Kiểm tra file có tồn tại không trước khi xóa để tránh lỗi
                if (Storage::disk('public')->exists($user->avatar_url)) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
            }

            // 2. Tạo tên file tùy chỉnh: avatar_{id}_{timestamp}.{ext}
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
            $path = 'avatars/' . $filename;

            // 3. Xử lý ảnh: Resize và Encode
            $manager = new ImageManager(new Driver());

            // Đọc file ảnh từ request
            $image = $manager->read($avatar);

            // Resize ảnh:
            // scale: Giữ nguyên tỷ lệ khung hình (aspect ratio).
            // Chiều rộng tối đa 300px, chiều cao tự tính.
            $image->scale(width: 300);

            // Encode sang định dạng JPG với chất lượng 80% (giảm dung lượng tiếp)
            $encodedImage = $image->toJpeg(quality: 80);

            // 4. Lưu vào Storage
            Storage::disk('public')->put($path, $encodedImage);

            // Cập nhật đường dẫn vào data
            $data['avatar_url'] = $path;
        }

        // Loại bỏ password rỗng nếu user không nhập
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Gọi repository để update vào DB
        return $this->userRepository->update($user, $data)->fresh();
    }
}
