<?php

namespace Tests\Feature;

use App\DTO\Cart\AddItemDTO;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 100,
        ]);

        $service = $this->app->make(CartService::class);

        $cart = $service->addItem($user, new AddItemDTO(
            productId: $product->id,
            quantity: 2
        ));

        $this->assertCount(1, $cart->items);
        $this->assertEquals(2, $cart->items->first()->quantity);
    }
}
