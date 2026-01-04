<?php

namespace App\Repositories\Eloquent\Api;

use App\Models\Category;
use App\Repositories\Interfaces\Api\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getList(?int $parentId = null): Collection
    {
        $columns = ['id', 'name', 'image_url', 'parent_id'];

        $query = Category::select($columns)
            ->where('is_active', true);

        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }

        // Load đệ quy
        return $query->with(['childrenRecursive' => function ($q) use ($columns) {
            $q->select($columns)
                ->where('is_active', true)
                ->orderBy('name', 'asc');
        }])
            ->orderBy('name', 'asc')
            ->get();
    }
}
