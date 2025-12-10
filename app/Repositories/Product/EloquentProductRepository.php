<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function paginateForListing(int $perPage = 12): LengthAwarePaginator
    {
        return Product::query()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
