<?php

namespace App\Repositories\Product;

namespace App\Repositories\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function paginateForListing(int $perPage = 12): LengthAwarePaginator;
}
