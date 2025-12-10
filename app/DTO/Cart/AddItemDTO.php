<?php

namespace App\DTO\Cart;

class AddItemDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity
    ) {}
}
