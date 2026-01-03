<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Hoạt động',
            self::INACTIVE => 'Chưa kích hoạt',
            self::BANNED => 'Bị khóa',
        };
    }
}