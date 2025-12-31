<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $repo
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }

    public function create(array $data): User
    {
        return DB::transaction(fn () => $this->repo->create($data));
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(fn () => $this->repo->update($user, $data));
    }

    public function delete(User $user): void
    {
        DB::transaction(fn () => $this->repo->delete($user));
    }
}
