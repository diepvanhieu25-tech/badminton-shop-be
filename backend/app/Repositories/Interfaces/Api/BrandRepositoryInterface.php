<?php

namespace App\Repositories\Interfaces\Api;

use Illuminate\Database\Eloquent\Collection;

interface BrandRepositoryInterface
{
    public function getAll(): Collection;
}
