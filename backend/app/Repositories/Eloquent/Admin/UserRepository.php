<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\User;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = User::query();

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

    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->delete(); // soft delete
    }
}
