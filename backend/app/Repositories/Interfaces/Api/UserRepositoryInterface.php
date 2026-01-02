<?php

namespace App\Repositories\Interfaces\Api;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function findByEmail(string $email); 
    public function update(User $user, array $data): User;
}