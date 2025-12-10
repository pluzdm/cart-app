<?php

namespace App\Http\Controllers;

use App\DTO\Cart\AddItemDTO;
use App\DTO\Cart\UpdateItemDTO;
use App\Http\Requests\CartAddItemRequest;
use App\Http\Requests\CartUpdateItemRequest;
use App\Services\Cart\CartService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    /**
     * @param Request $request
     * @return Factory|View|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $cart = $this->cartService->getCart($request->user());
        return view('cart.show', compact('cart'));
    }

    /**
     * @param CartAddItemRequest $request
     * @return RedirectResponse
     */
    public function add(CartAddItemRequest $request)
    {
        $dto = new AddItemDTO(
            productId: $request->integer('product_id'),
            quantity: $request->integer('quantity'),
        );
        $this->cartService->addItem($request->user(), $dto);
        return redirect()->route('cart.show')->with('status', 'Product added to cart');
    }

    /**
     * @param CartUpdateItemRequest $request
     * @return RedirectResponse
     */
    public function update(CartUpdateItemRequest $request)
    {
        $dto = new UpdateItemDTO(
            productId: $request->integer('product_id'),
            quantity: $request->integer('quantity'),
        );
        $this->cartService->updateItem($request->user(), $dto);
        return redirect()->route('cart.show')->with('status', 'Cart updated');
    }

    /**
     * @param Request $request
     * @param int $productId
     * @return RedirectResponse
     */
    public function remove(Request $request, int $productId)
    {
        $this->cartService->removeItem($request->user(), $productId);
        return redirect()->route('cart.show')->with('status', 'Product removed');
    }
}
