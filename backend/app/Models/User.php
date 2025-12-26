<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <--- QUAN TRỌNG: Import Sanctum

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <--- Thêm Trait HasApiTokens

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',          // Mới thêm
        'avatar_url',     // Mới thêm
        'role',           // Mới thêm
        'status',         // Mới thêm
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Quan hệ: 1 User có nhiều tài khoản MXH
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }
}