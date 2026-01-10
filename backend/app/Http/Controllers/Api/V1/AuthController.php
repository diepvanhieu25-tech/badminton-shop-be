<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Resources\Api\UserResource;
use App\Services\Api\AuthService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;

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
        $cookie = $this->makeAuthCookie($result['access_token']);

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
        $cookie = $this->makeAuthCookie($result['access_token']);

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
        // 1. Xóa token của thiết bị hiện tại (Nếu user có đăng nhập)
        $request->user()?->currentAccessToken()->delete();

        // 2. Xóa Cookie và trả về JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng xuất thành công',
        ])->withCookie(cookie()->forget('access_token'));
    }

    /**
     * Bước 1: Redirect user sang trang Google/Facebook
     */
    public function redirectToProvider($provider)
    {
        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
        $driver = Socialite::driver($provider);

        return $driver->stateless()->redirect();
    }

    /**
     * Bước 2: Google redirect ngược về đây kèm thông tin User
     */
    public function handleProviderCallback($provider)
    {
        try {
            $driver = Socialite::driver($provider);
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $socialUser = $driver->stateless()->user();

            // Service xử lý tìm/tạo user
            $result = $this->authService->handleSocialCallback($provider, $socialUser);

            // Tạo Cookie
            $cookie = $this->makeAuthCookie($result['access_token']);

            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');

            // THÊM: ?login_social=success để báo hiệu cho Frontend
            return redirect()->to($frontendUrl . '/?login_social=success')
                ->withCookie($cookie);
        } catch (\Exception $e) {
            // Nếu lỗi thì về lại trang login kèm thông báo
            return redirect()->to(env('FRONTEND_URL', 'http://localhost:3000') . '/dang-nhap?error=social_failed');
        }
    }

    private function makeAuthCookie($token)
    {
        return cookie(
            'access_token',
            $token,
            60 * 24 * 30,
            '/',
            null,
            true,
            true,
            false,
            'Strict'
        );
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->forgotPassword($request->email);

            return response()->json([
                'success' => true,
                'message' => 'Vui lòng kiểm tra email để lấy lại mật khẩu.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * API: POST /auth/reset-password
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->resetPassword(
                $request->email,
                $request->token,
                $request->password
            );

            return response()->json([
                'success' => true,
                'message' => 'Mật khẩu đã được đặt lại thành công.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
