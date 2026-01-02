<?php

namespace App\Repositories\Eloquent\Api;

use App\Models\User;
use App\Repositories\Interfaces\Api\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
}
