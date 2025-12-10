<?php

namespace App\Services\Cart;

use App\DTO\Cart\AddItemDTO;
use App\DTO\Cart\UpdateItemDTO;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\DatabaseManager;

readonly class CartService
{
    public function __construct(
        private CartResolver    $cartResolver,
        private DatabaseManager $db,
    ) {}

    public function getCart(?Authenticatable $user): Cart
    {
        return $this->cartResolver->resolve($user)->load('items.product');
    }

    public function addItem(?Authenticatable $user, AddItemDTO $dto): Cart
    {
        return $this->db->transaction(function () use ($user, $dto) {
            $cart = $this->cartResolver->resolve($user);

            $product = Product::findOrFail($dto->productId);

            $item = $cart->items()->firstOrNew([
                'product_id' => $product->id,
            ]);

            $item->quantity = ($item->exists ? $item->quantity : 0) + $dto->quantity;
            $item->save();

            return $cart->fresh('items.product');
        });
    }

    public function updateItem(?Authenticatable $user, UpdateItemDTO $dto): Cart
    {
        return $this->db->transaction(function () use ($user, $dto) {
            $cart = $this->cartResolver->resolve($user);

            $item = $cart->items()->where('product_id', $dto->productId)->firstOrFail();
            $item->quantity = $dto->quantity;

            if ($item->quantity <= 0) {
                $item->delete();
            } else {
                $item->save();
            }

            return $cart->fresh('items.product');
        });
    }

    public function removeItem(?Authenticatable $user, int $productId): Cart
    {
        return $this->db->transaction(function () use ($user, $productId) {
            $cart = $this->cartResolver->resolve($user);

            $cart->items()->where('product_id', $productId)->delete();

            return $cart->fresh('items.product');
        });
    }
}
