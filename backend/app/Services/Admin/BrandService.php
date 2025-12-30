<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Repositories\Interfaces\Admin\BrandRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BrandService
{
    public function __construct(
        private readonly BrandRepositoryInterface $repo
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }

    public function create(array $data): Brand
    {
        return DB::transaction(fn () => $this->repo->create($data));
    }

    public function update(Brand $brand, array $data): Brand
    {
        return DB::transaction(fn () => $this->repo->update($brand, $data));
    }

    public function delete(Brand $brand): void
    {
        DB::transaction(fn () => $this->repo->delete($brand));
    }
}
