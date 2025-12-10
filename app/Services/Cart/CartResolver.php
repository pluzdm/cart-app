<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Repositories\Cart\CartRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

readonly class CartResolver
{
    public function __construct(
        private CartRepositoryInterface $carts,
        private Request                 $request,
    ) {}

    public function resolve(?Authenticatable $user): Cart
    {
        $guestToken = $this->getOrCreateGuestToken();

        // if there is an authorized user - we look for his cart
        if ($user) {
            $cart = $this->carts->findByUserId($user->getAuthIdentifier());

            if (!$cart) {
                // if there was a guest cart - bind to user
                $guestCart = $this->carts->findByGuestToken($guestToken);

                if ($guestCart) {
                    $guestCart->user_id = $user->getAuthIdentifier();
                    $guestCart->guest_token = null;
                    $this->carts->save($guestCart);
                    return $guestCart;
                }

                return $this->carts->createForUser($user->getAuthIdentifier(), null);
            }

            return $cart;
        }

        // guest cart
        $cart = $this->carts->findByGuestToken($guestToken);

        if (!$cart) {
            $cart = $this->carts->createForUser(null, $guestToken);
        }

        return $cart;
    }

    private function getOrCreateGuestToken(): string
    {
        $cookieName = 'guest_cart_token';
        $token = $this->request->cookie($cookieName);

        if (!$token) {
            $token = bin2hex(random_bytes(16));
            cookie()->queue(cookie($cookieName, $token, 60 * 24 * 30)); // 30 днів
        }

        return $token;
    }
}
