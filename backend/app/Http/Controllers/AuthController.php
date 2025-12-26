<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        // 1. Dữ liệu đã được validate ở RegisterRequest
        $validatedData = $request->validated();

        // 2. Gọi Service xử lý
        $result = $this->authService->register($validatedData);

        // 3. Trả về response chuẩn
        return response()->json([
            'success' => true,
            'message' => 'Đăng ký tài khoản thành công',
            'data' => [
                'user' => new UserResource($result['user']), // Format qua Resource
                'access_token' => $result['token'],
                'token_type' => 'Bearer',
            ]
        ], 201);
    }
}