<?php

namespace App\Repositories\Eloquent\Api;

use App\Models\Category;
use App\Repositories\Interfaces\Api\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getList(?int $parentId = null): Collection
    {
        // 1. Chỉ lấy cột cần thiết (Performance optimization)
        // Lưu ý: Phải select 'id' và 'parent_id' để Eloquent ghép nối quan hệ
        $columns = ['id', 'name', 'image_url', 'parent_id'];

        $query = Category::select($columns)
            ->where('is_active', true);

        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            // Mặc định lấy danh mục gốc (Level 1)
            $query->whereNull('parent_id');
        }

        // 2. Load đệ quy toàn bộ cấp con cháu
        return $query->with(['childrenRecursive' => function ($q) use ($columns) {
                $q->select($columns) // Select cột cho cấp con luôn
                  ->where('is_active', true)
                  ->orderBy('name', 'asc');
            }])
            ->orderBy('name', 'asc')
            ->get();
    }
}