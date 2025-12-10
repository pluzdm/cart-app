<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service
    ) {}

    /**
     * @return Factory|View|\Illuminate\View\View
     */
    public function index()
    {
        $products = $this->service->getCatalogPage(12);

        return view('products.index', compact('products'));
    }
}
