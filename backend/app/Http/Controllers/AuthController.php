<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SocialLoginRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    private function makeAuthCookie($token)
    {
        return cookie(
            'access_token',
            $token,               // Giá trị token
            60 * 24 * 30,         // Thời gian sống (phút) - Ví dụ: 30 ngày
            '/',                  // Path
            null,                 // Domain (null để tự nhận)
            false,                 // Secure (true = chỉ gửi qua HTTPS) - Chỉnh thành false nếu test localhost http thường
            true,                 // HttpOnly (QUAN TRỌNG: JS không đọc được)
            false,                // Raw
            'Lax'                 // SameSite (Lax hoặc None)
        );
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $result = $this->authService->register($validatedData);

        // Tạo Cookie từ token trả về
        $cookie = $this->makeAuthCookie($result['token']);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'user' => new UserResource($result['user']),
            ]
        ], 201)->withCookie($cookie); // Gắn cookie vào response
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (! $result['ok']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $result['status']);
        }

        // Tạo Cookie
        $cookie = $this->makeAuthCookie($result['token']);

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => new UserResource($result['user']),
            ]
        ], 200)->withCookie($cookie);
    }

    public function socialLogin(SocialLoginRequest $request): JsonResponse
    {
        $result = $this->authService->loginWithSocial($request->validated());

        if (! $result['ok']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $result['status']);
        }

        $cookie = $this->makeAuthCookie($result['token']);

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập Social thành công',
            'data' => [
                'user' => new UserResource($result['user']),
            ]
        ], 200)->withCookie($cookie);
    }

    public function logout(Request $request): JsonResponse
    {
        // 1. Gọi Service xóa token trong DB
        $user = $request->user();
        if ($user) {
            $this->authService->logout($user);
        }

        // 2. Xóa Cookie ở trình duyệt
        $cookie = Cookie::forget('access_token');

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công',
        ], 200)->withCookie($cookie);
    }
}
