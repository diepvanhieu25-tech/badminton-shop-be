<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getList(?int $parentId = null): Collection
    {
        $query = Category::where('is_active', true);

        if ($parentId) {
            // Trường hợp 1: Lấy các danh mục con của một danh mục cụ thể
            $query->where('parent_id', $parentId);
        } else {
            // Trường hợp 2: Mặc định -> Lấy các danh mục gốc (Cấp 1)
            $query->whereNull('parent_id');
        }

        return $query->with(['children' => function ($q) {
            $q->where('is_active', true)->orderBy('name', 'asc');
        }])
            ->orderBy('name', 'asc')
            ->get();
    }
}
