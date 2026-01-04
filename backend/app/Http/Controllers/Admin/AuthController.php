<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Hiển thị form đăng nhập
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === UserRole::ADMIN) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login'); // Trả về view bạn đã tạo ở câu hỏi đầu tiên
    }

    // 2. Xử lý đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Kiểm tra xem có phải Admin không?
            if (Auth::user()->role === UserRole::ADMIN) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // Nếu đăng nhập đúng pass nhưng role không phải admin -> Logout ngay
            Auth::logout();
            return back()->withErrors(['email' => 'Tài khoản của bạn không có quyền truy cập quản trị.']);
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    // 3. Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}