<?php

namespace App\Services\Api;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\Api\UserRepositoryInterface;
use Laravel\Socialite\Contracts\User as SocialUser;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    // 1. Hàm xử lý Đăng Ký
    public function register(array $data): array
    {
        // Setup default
        $data['role'] = UserRole::CUSTOMER;
        $data['status'] = UserStatus::ACTIVE;

        // Create User
        $user = $this->userRepository->create($data);

        // Gọi hàm tái sử dụng để tạo token và trả về kết quả
        return $this->createTokenAndFormatResponse($user);
    }

    // 2. Hàm xử lý Đăng Nhập (Ví dụ để bạn hình dung cách tái sử dụng)
    public function login(array $credentials): array
    {
        // 1. Tìm user qua email
        $user = $this->userRepository->findByEmail($credentials['email']);

        // 2. Kiểm tra user có tồn tại VÀ mật khẩu có khớp không
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            // Ném ra lỗi Validation (sẽ trả về mã 422 cho Frontend)
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        // 3. (Tùy chọn) Kiểm tra xem user có bị Ban không
        // if ($user->status === UserStatus::BANNED) { ... }

        // 4. Tái sử dụng hàm tạo Token đã viết ở bài trước
        return $this->createTokenAndFormatResponse($user);
    }

    public function logout(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        //  Đăng xuất khỏi TOÀN BỘ thiết bị
        $user->tokens()->delete();
    }

    // --- HÀM TÁI SỬ DỤNG (CORE) ---
    /**
     * Tạo token và format dữ liệu trả về cho Controller
     */
    protected function createTokenAndFormatResponse(User $user): array
    {
        
        // Tạo Token mới
        $token = $user->createToken('access_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }

    /**
     * Xử lý callback từ Socialite
     */
    public function handleSocialCallback(string $provider, SocialUser $sUser): array
    {
        return DB::transaction(function () use ($provider, $sUser) {
            // 1. Kiểm tra xem tài khoản MXH này đã tồn tại trong hệ thống chưa
            $account = SocialAccount::where('provider', $provider)
                ->where('provider_user_id', $sUser->getId())
                ->first();

            if ($account) {
                // Trường hợp 1: Đã từng đăng nhập bằng GG/FB này -> Lấy user ra
                $user = $account->user;
            } else {
                // Trường hợp 2: Chưa có link MXH này.
                // Kiểm tra xem Email của GG/FB này có trùng với User nào đang có không?
                $email = $sUser->getEmail();
                $user = $this->userRepository->findByEmail($email);

                if (! $user) {
                    // Trường hợp 2a: User hoàn toàn mới -> Tạo User mới
                    // Lưu ý: Password để random vì họ đăng nhập bằng GG, không dùng pass
                    $user = $this->userRepository->create([
                        'name' => $sUser->getName(),
                        'email' => $email,
                        'password' => Str::random(16), 
                        'phone' => null, // MXH thường không trả về phone, chấp nhận null hoặc xử lý sau
                        'avatar_url' => $sUser->getAvatar(),
                        'role' => UserRole::CUSTOMER,
                        'status' => UserStatus::ACTIVE,
                    ]);
                }

                // Tạo liên kết vào bảng social_accounts (cho cả 2a và trường hợp user cũ link thêm MXH)
                $user->socialAccounts()->create([
                    'provider' => $provider,
                    'provider_user_id' => $sUser->getId(),
                    'provider_email' => $email,
                ]);
            }

            // 3. Tái sử dụng hàm tạo Token và Cookie (đã viết ở bài trước)
            return $this->createTokenAndFormatResponse($user);
        });
    }
}