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

    public function getCategories(array $params)
    {
        $parentId = $params['parent_id'] ?? null;
        
        $cacheKey = 'api_categories_' . ($parentId ?? 'root');

        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($parentId) {
            return $this->categoryRepo->getList($parentId);
        });
    }
}