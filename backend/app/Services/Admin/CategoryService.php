<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Repositories\Interfaces\Admin\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repo
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }

    public function create(array $data): Category
    {
        return DB::transaction(fn () => $this->repo->create($data));
    }

    public function update(Category $category, array $data): Category
    {
        return DB::transaction(fn () => $this->repo->update($category, $data));
    }

    public function delete(Category $category): void
    {
        DB::transaction(fn () => $this->repo->delete($category));
    }
}
