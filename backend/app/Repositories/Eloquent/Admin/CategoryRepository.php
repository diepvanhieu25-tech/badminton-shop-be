<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Category;
use App\Repositories\Interfaces\Admin\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Category::query();

        // search theo name (?q=yonex)
        if (!empty($filters['q'])) {
            $search = trim((string) $filters['q']);
            $q->where('name', 'like', "%{$search}%");
        }

        // filter active (?is_active=1|0)
        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null && $filters['is_active'] !== '') {
            $q->where('is_active', (bool) $filters['is_active']);
        }

        return $q->orderByDesc('id')->paginate($perPage);
    }

    public function create(array $data): Category
    {
        return Category::query()->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->fill($data);
        $category->save();

        return $category->refresh();
    }

    public function delete(Category $category): void
    {
        $category->delete(); // soft delete
    }
}
