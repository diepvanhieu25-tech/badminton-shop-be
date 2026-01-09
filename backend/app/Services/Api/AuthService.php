<?php

namespace App\Services\Api;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\SocialAccount;
use App\Models\User;
use App\Repositories\Interfaces\Api\PasswordResetRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\Api\UserRepositoryInterface;
use Laravel\Socialite\Contracts\User as SocialUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Exception;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected PasswordResetRepositoryInterface $passwordResetRepo
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

    public function forgotPassword(string $email)
    {
        // 1. Kiểm tra email có tồn tại trong hệ thống không
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new Exception('Email không tồn tại trong hệ thống.');
        }

        // 2. Tạo token ngẫu nhiên
        $token = Str::random(60);

        // 3. Lưu token vào DB
        $this->passwordResetRepo->createToken($email, $token);

        // 4. Gửi email qua Mailtrap
        // Sử dụng Queue (queue()) nếu muốn chạy nền, ở đây dùng send() cho đơn giản
        Mail::to($email)->send(new ResetPasswordMail($token, $email));

        return true;
    }

    /**
     * Logic 2: Đặt lại mật khẩu mới
     */
    public function resetPassword(string $email, string $token, string $newPassword)
    {
        // 1. Kiểm tra Token có hợp lệ không
        $record = $this->passwordResetRepo->findToken($email, $token);
        if (!$record) {
            throw new Exception('Token không hợp lệ hoặc đã hết hạn.');
        }

        // 2. Lấy user
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new Exception('Người dùng không tồn tại.');
        }

        // 3. Cập nhật mật khẩu mới (Hash)
        $this->userRepository->update($user, [
            'password' => Hash::make($newPassword)
        ]);

        // 4. Xóa token để không dùng lại được nữa
        $this->passwordResetRepo->deleteToken($email);

        return true;
    }
}
