<?php 
namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Kiểm tra role có phải admin không
        // (Lưu ý: user() lấy từ bảng users của bạn)
        if (Auth::user()->role === UserRole::ADMIN) {
            return $next($request); // Cho qua
        }

        // 3. Nếu không phải admin thì đá về trang chủ hoặc báo lỗi 403
        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang quản trị!');
        // hoặc: abort(403);
    }
}