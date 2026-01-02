<?php

namespace App\Repositories\Eloquent\Api;

use App\Models\Brand;
use App\Repositories\Interfaces\Api\BrandRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository implements BrandRepositoryInterface
{
    public function getAll(): Collection
    {
        return Brand::select('id', 'name', 'logo_url') // Chỉ lấy cột cần thiết
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }
}
