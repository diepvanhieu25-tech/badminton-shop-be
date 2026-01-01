<?php

namespace App\Enums;

enum ProductStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Nháp',
            self::ACTIVE => 'Đang bán',
            self::INACTIVE => 'Ngừng kinh doanh',
        };
    }
}