<?php

namespace App\Repositories\Cart;

use App\Models\Cart;

interface CartRepositoryInterface
{
    public function findByUserId(?int $userId): ?Cart;

    public function findByGuestToken(?string $guestToken): ?Cart;

    public function createForUser(?int $userId, ?string $guestToken): Cart;

    public function save(Cart $cart): void;
}
