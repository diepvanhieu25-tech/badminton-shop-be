<?php

namespace App\Services;

use App\Repositories\Interfaces\BrandRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BrandService
{
    protected $brandRepo;

    public function __construct(BrandRepositoryInterface $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    public function getAllBrands(): Collection
    {
        return $this->brandRepo->getAll();
    }
}
