<?php

namespace App\Repositories\Interfaces\Api;

interface CartRepositoryInterface
{
    public function findActiveCart(int $userId);
    public function firstOrCreateCart(int $userId);
    public function findCartItem($cartId, $variantId);
    public function createOrUpdateItem($cartId, $variantId, $quantity, $isUpdate = false);
    public function getVariantStock($variantId); // Helper để check tồn kho
}
