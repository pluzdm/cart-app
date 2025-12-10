<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $products
    ) {}

    public function getCatalogPage(int $perPage = 12): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->products->paginateForListing($perPage);
    }
}
