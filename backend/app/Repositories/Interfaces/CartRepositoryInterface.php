<?php

namespace App\Repositories\Interfaces;

interface CartRepositoryInterface
{
    public function findActiveCartByUserId(int $userId);
}
