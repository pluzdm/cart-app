<?php

namespace App\Repositories\Cart;

use App\Models\Cart;

class EloquentCartRepository implements CartRepositoryInterface
{
    public function findByUserId(?int $userId): ?Cart
    {
        if ($userId === null) {
            return null;
        }

        return Cart::whereUserId( $userId)->first();
    }

    public function findByGuestToken(?string $guestToken): ?Cart
    {
        if (!$guestToken) {
            return null;
        }

        return Cart::whereGuestToken($guestToken)->first();
    }

    public function createForUser(?int $userId, ?string $guestToken): Cart
    {
        return Cart::create([
            'user_id' => $userId,
            'guest_token' => $guestToken,
        ]);
    }

    public function save(Cart $cart): void
    {
        $cart->save();
    }
}
