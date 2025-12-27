<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

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
        $user = User::where('email', $data['email'])->first();

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

    public function logout(User $user)
    {
        $token = $user->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return true;
    }
}
