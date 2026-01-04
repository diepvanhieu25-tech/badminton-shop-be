<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cookie;

class SocialAuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Bước 1: Redirect user sang trang Google/Facebook
     */
    public function redirectToProvider($provider)
    {
        $driver = Socialite::driver($provider);

        // --- FIX LỖI BÁO ĐỎ 'stateless' ---
        // Dòng này giúp VS Code hiểu $driver là AbstractProvider có hàm stateless()
        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */

        return $driver->stateless()->redirect();
    }

    /**
     * Bước 2: Google redirect ngược về đây kèm thông tin User
     */
    public function handleProviderCallback($provider)
    {
        try {
            $driver = Socialite::driver($provider);

            // --- FIX LỖI BÁO ĐỎ 'stateless' ---
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $socialUser = $driver->stateless()->user();

            // Gọi Service xử lý logic tìm/tạo user
            $result = $this->authService->handleSocialCallback($provider, $socialUser);

            // Tạo Cookie HttpOnly
            $cookie = cookie(
                'access_token',
                $result['access_token'],
                60 * 24 * 30, // 30 ngày
                '/',
                null,
                true, // Secure
                true, // HttpOnly
                false,
                'Lax'
            );

            // Redirect về Frontend
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');

            return redirect()->to($frontendUrl . '/')
                ->withCookie($cookie);
        } catch (\Exception $e) {
            // Log lỗi
            \Illuminate\Support\Facades\Log::error("Social Login Error: " . $e->getMessage());

            // Redirect về trang login FE kèm lỗi
            return redirect()->to(env('FRONTEND_URL', 'http://localhost:3000') . '/login');
        }
    }
}
