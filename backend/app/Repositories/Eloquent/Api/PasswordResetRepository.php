<?php

namespace App\Repositories\Eloquent\Api;

use App\Repositories\Interfaces\Api\PasswordResetRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    protected $table = 'password_reset_tokens';

    public function createToken(string $email, string $token)
    {
        // Xóa token cũ nếu có
        $this->deleteToken($email);

        // Tạo token mới
        return DB::table($this->table)->insert([
            'email' => $email,
            'token' => $token, // Lưu token (có thể hash nếu muốn bảo mật cao hơn)
            'created_at' => Carbon::now()
        ]);
    }

    public function findToken(string $email, string $token)
    {
        // Tìm record khớp email và token
        $record = DB::table($this->table)
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        // Kiểm tra xem token có hết hạn không (ví dụ: 5 phút)
        if ($record && Carbon::parse($record->created_at)->addMinutes(5)->isPast()) {
            $this->deleteToken($email);
            return null;
        }

        return $record;
    }

    public function deleteToken(string $email)
    {
        return DB::table($this->table)->where('email', $email)->delete();
    }
}
