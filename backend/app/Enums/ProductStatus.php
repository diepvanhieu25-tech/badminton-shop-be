<?php
namespace App\Enums;

enum ProductStatus: string {
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}