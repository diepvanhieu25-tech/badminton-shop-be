<?php

namespace App\Repositories\Interfaces\Api;

interface PasswordResetRepositoryInterface
{
    // Tạo hoặc cập nhật token cho email
    public function createToken(string $email, string $token);

    // Kiểm tra token có hợp lệ không
    public function findToken(string $email, string $token);

    // Xóa token sau khi dùng xong
    public function deleteToken(string $email);
}
