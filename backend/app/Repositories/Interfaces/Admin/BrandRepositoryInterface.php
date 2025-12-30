<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Brand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BrandRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): Brand;

    public function update(Brand $brand, array $data): Brand;

    public function delete(Brand $brand): void;
}
