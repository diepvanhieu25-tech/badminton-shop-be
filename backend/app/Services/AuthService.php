<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Socialite;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            // 1. Hash mật khẩu
            $data['password'] = Hash::make($data['password']);

            // 2. Gọi Repository để tạo user
            $user = $this->userRepository->create($data);

            // 3. Tạo token (Sanctum) ngay sau khi đăng ký để login luôn
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return [
                'user' => $user,
                'token' => $token
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi đăng ký: ' . $e->getMessage());
            throw $e;
        }
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        // 1. Kiểm tra User + Password
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return [
                'ok' => false,
                'status' => 401,
                'message' => 'Thông tin đăng nhập không chính xác.',
            ];
        }

        // 2. Kiểm tra trạng thái User
        if (($user->status ?? 'active') !== 'active') {
            return [
                'ok' => false,
                'status' => 403,
                'message' => 'Tài khoản của bạn đang bị khóa.',
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'ok' => true,
            'status' => 200,
            'user' => $user,
            'token' => $token,
        ];
    }

    public function loginWithSocial(array $data)
    {
        $provider = $data['provider'];
        $token = $data['access_token'];

        DB::beginTransaction();
        try {
            // 1. Lấy thông tin từ Google/Facebook
            // (Đoạn này vẫn dùng Socialite vì nó là logic xác thực bên thứ 3, ko phải DB)
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver($provider);
            $socialUser = $driver->stateless()->userFromToken($token);

            // 2. Tìm User dựa trên Social ID + Provider
            $user = $this->userRepository->findUserBySocialId($provider, $socialUser->getId());

            // 3. Nếu chưa từng login
            if (! $user) {
                // Thử tìm theo Email trước (tránh tạo trùng)
                $user = $this->userRepository->findByEmail($socialUser->getEmail());

                // Nếu vẫn không có -> Tạo User mới qua Repository
                if (! $user) {
                    $user = $this->userRepository->create([
                        'email' => $socialUser->getEmail(),
                        'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                        'status' => 'active',
                    ]);
                }

                // Tạo liên kết Social Account qua Repository
                $this->userRepository->createSocialAccount([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_user_id' => $socialUser->getId(),
                    'provider_email' => $socialUser->getEmail(),
                ]);
            }

            // 4. Kiểm tra khóa tài khoản (Logic nghiệp vụ)
            if (($user->status ?? 'active') !== 'active') {
                return ['ok' => false, 'status' => 403, 'message' => 'Tài khoản bị khóa.'];
            }

            // 5. Tạo Token
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return [
                'ok' => true,
                'status' => 200,
                'user' => $user,
                'token' => $token
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Social Login Error: " . $e->getMessage());
            return ['ok' => false, 'status' => 401, 'message' => 'Lỗi xác thực Social.'];
        }
    }

    public function logout(User $user)
    {
        $token = $user->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return true;
    }
}
