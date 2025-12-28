<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface BrandRepositoryInterface
{
    public function getAll(): Collection;
}
