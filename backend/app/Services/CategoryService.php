<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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

        return $this->categoryRepo->getList($parentId);
    }
}
