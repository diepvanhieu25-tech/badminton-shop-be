<?php

namespace App\Services\Api;

use App\Repositories\Interfaces\Api\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getCategories(array $validatedData): Collection
    {
        $parentId = $validatedData['parent_id'] ?? null;

        // Tạo Key Cache: category_tree_root hoặc category_tree_5
        $cacheKey = 'category_tree_' . ($parentId ?? 'root');

        // Cache 1 ngày (86400 giây)
        return Cache::remember($cacheKey, 86400, function () use ($parentId) {
            return $this->categoryRepo->getList($parentId);
        });
    }
}