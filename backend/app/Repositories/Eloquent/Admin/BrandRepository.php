<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Brand;
use App\Repositories\Interfaces\Admin\BrandRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BrandRepository implements BrandRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Brand::query();

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

    public function create(array $data): Brand
    {
        return Brand::query()->create($data);
    }

    public function update(Brand $brand, array $data): Brand
    {
        $brand->fill($data);
        $brand->save();

        return $brand->refresh();
    }

    public function delete(Brand $brand): void
    {
        $brand->delete(); // soft delete
    }
}
