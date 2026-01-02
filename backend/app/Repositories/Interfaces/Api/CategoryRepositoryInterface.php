<?php

namespace App\Repositories\Interfaces\Api;

use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getList(?int $parentId = null): Collection;
}
