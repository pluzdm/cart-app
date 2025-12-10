@extends('layouts.app')

@section('content')
    <h1 class="text-2xl mb-4">Products</h1>
    <div class="grid grid-cols-3 gap-4">
        @foreach($products as $product)
            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-bold">{{ $product->name }}</h2>
                <p class="text-sm text-gray-600">{{ $product->description }}</p>
                <p class="mt-2 font-semibold">{{ number_format($product->price, 2) }} грн</p>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-2 flex">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" value="1" min="1" class="border rounded px-2 w-16 mr-2">
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
                        Add to cart
                    </button>
                </form>
            </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
