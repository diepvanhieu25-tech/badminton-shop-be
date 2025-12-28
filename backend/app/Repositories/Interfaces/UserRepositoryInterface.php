<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function create(array $data);

    // Tìm User theo Email
    public function findByEmail(string $email);

    // Tìm User dựa trên thông tin Social
    public function findUserBySocialId(string $provider, string $providerUserId);

    // Tạo liên kết Social Account mới vào bảng social_accounts
    public function createSocialAccount(array $data);
}
