<?php

namespace App\Services\Api;

use App\Repositories\Interfaces\Api\BrandRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class BrandService
{
    protected $brandRepo;

    public function __construct(BrandRepositoryInterface $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    public function getAllBrands(): Collection
    {
        return Cache::remember('api_brands_list', 60 * 60 * 24, function () {
            return $this->brandRepo->getAll();
        });
    }
}
