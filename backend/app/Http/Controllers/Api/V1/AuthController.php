<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\UserResource;
use App\Services\Api\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function register(RegisterRequest $request)
    {
        // 1. Validate
        $validatedData = $request->validated();

        // 2. Service xử lý (Trả về mảng gồm user và access_token)
        $result = $this->authService->register($validatedData);

        // 3. Tạo Cookie HttpOnly (FE không đọc được, nhưng Browser tự lưu)
        $cookie = cookie(
            'access_token',          // Key
            $result['access_token'], // Value
            60 * 24 * 30,            // 30 ngày
            '/',                     // Path
            null,                    // Domain
            true,                    // Secure (HTTPS)
            true,                    // HttpOnly -> QUAN TRỌNG
            false,                   // Raw
            'Strict'                 // SameSite
        );

        // 4. Return JSON + Cookie
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công',
            'data' => new UserResource($result['user']),
        ], 201)->withCookie($cookie);
    }

    public function login(LoginRequest $request)
    {
        // 1. Validate
        $credentials = $request->validated();

        // 2. Gọi Service xử lý (Nếu sai pass service sẽ tự throw lỗi)
        $result = $this->authService->login($credentials);

        // 3. Tạo Cookie (Copy y chang bên Register)
        $cookie = cookie(
            'access_token',
            $result['access_token'],
            60 * 24 * 30, // 30 ngày
            '/',
            null,
            true,
            true,
            false,
            'Strict'
        );

        // 4. Trả về kết quả
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng nhập thành công',
            'access_token' => $result['access_token'],
            'token_type' => 'Bearer',
            'data' => new UserResource($result['user']),
        ], 200)->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        // 1. Xóa token trong DB
        $this->authService->logout();

        // 2. Tạo lệnh xóa Cookie
        $cookie = Cookie::forget('access_token');

        // 3. Trả về response kèm lệnh xóa cookie
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng xuất thành công',
        ], 200)->withCookie($cookie);
    }
}