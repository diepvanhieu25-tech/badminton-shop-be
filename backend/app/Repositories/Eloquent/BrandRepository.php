<?php

namespace App\Repositories\Eloquent;

use App\Models\Brand;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository implements BrandRepositoryInterface
{
    public function getAll(): Collection
    {
        // Lấy tất cả brand đang hoạt động, sắp xếp theo tên
        return Brand::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }
}
