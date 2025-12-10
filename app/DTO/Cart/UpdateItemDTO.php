<?php

namespace App\DTO\Cart;

class UpdateItemDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity
    ) {}
}
