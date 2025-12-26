<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
}