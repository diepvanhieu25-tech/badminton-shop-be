<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AttachTokenFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu request chưa có Header Authorization và có Cookie access_token
        if (!$request->headers->has('Authorization') && $request->hasCookie('access_token')) {
            $token = $request->cookie('access_token');
            // Gắn thủ công vào Header để Sanctum validate
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}
