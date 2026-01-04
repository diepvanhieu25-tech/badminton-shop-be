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
        return DB::transaction(function () use ($data) {
            // 1. Chuẩn bị dữ liệu
            $payload = [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'role'     => UserRole::CUSTOMER,
                'status'   => UserStatus::ACTIVE,
                'avatar_url' => $data['avatar_url'] ?? null,
            ];

            // 2. Tạo User
            $user = $this->userRepository->create($payload);

            // 3. Tạo Token (Nếu lỗi ở đây, DB::transaction sẽ rollback bước 2)
            return $this->createTokenAndFormatResponse($user);
        });
    }

    // 2. Hàm xử lý Đăng Nhập
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        // 1. Kiểm tra User tồn tại và Mật khẩu khớp
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        // 2. TỐI ƯU: Kiểm tra User có đang hoạt động không
        if ($user->status !== UserStatus::ACTIVE) {
            throw ValidationException::withMessages([
                'email' => ['Tài khoản của bạn đã bị khóa hoặc chưa kích hoạt.'],
            ]);
        }

        return $this->createTokenAndFormatResponse($user);
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
    public function handleSocialCallback(string $provider, $sUser): array
    {
        return DB::transaction(function () use ($provider, $sUser) {
            $account = SocialAccount::where('provider', $provider)
                ->where('provider_user_id', $sUser->getId())
                ->first();

            if ($account) {
                $user = $account->user;
            } else {
                $email = $sUser->getEmail();
                $user = $this->userRepository->findByEmail($email);

                if (! $user) {
                    // Tạo user mới
                    $user = $this->userRepository->create([
                        'name' => $sUser->getName(),
                        'email' => $email,
                        'password' => Str::random(16),
                        'avatar_url' => $sUser->getAvatar(),
                        'role' => UserRole::CUSTOMER,
                        'status' => UserStatus::ACTIVE,
                        'email_verified_at' => now(),
                    ]);
                }

                // Link tài khoản MXH
                $user->socialAccounts()->create([
                    'provider' => $provider,
                    'provider_user_id' => $sUser->getId(),
                    'provider_email' => $email,
                ]);
            }

            // --- TỐI ƯU BẢO MẬT: Chặn User bị khóa ---
            if ($user->status !== UserStatus::ACTIVE) {
                throw new \Exception('Tài khoản của bạn đã bị khóa.');
            }

            return $this->createTokenAndFormatResponse($user);
        });
    }
}
