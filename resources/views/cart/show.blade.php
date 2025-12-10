@extends('layouts.app')

@section('content')
    <h1 class="text-2xl mb-4">Your cart</h1>
    @if($cart->items->isEmpty())
        <p>Cart is empty.</p>
    @else
        <table class="min-w-full bg-white rounded shadow">
            <thead>
            <tr>
                <th class="px-4 py-2 text-left">Product</th>
                <th class="px-4 py-2">Price</th>
                <th class="px-4 py-2">Qty</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @php $sum = 0; @endphp
            @foreach($cart->items as $item)
                @php
                    $lineTotal = $item->product->price * $item->quantity;
                    $sum += $lineTotal;
                @endphp
                <tr>
                    <td class="border px-4 py-2">{{ $item->product->name }}</td>
                    <td class="border px-4 py-2 text-center">{{ number_format($item->product->price, 2) }}</td>
                    <td class="border px-4 py-2 text-center">
                        <form action="{{ route('cart.update') }}" method="POST" class="inline-flex">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" class="border rounded px-2 w-16 mr-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded">Update</button>
                        </form>
                    </td>
                    <td class="border px-4 py-2 text-center">{{ number_format($lineTotal, 2) }}</td>
                    <td class="border px-4 py-2 text-center">
                        <form action="{{ route('cart.remove', $item->product_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-3 py-1 rounded">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="border px-4 py-2 text-right font-bold">Total:</td>
                <td class="border px-4 py-2 text-center font-bold">{{ number_format($sum, 2) }}</td>
                <td class="border px-4 py-2"></td>
            </tr>
            </tbody>
        </table>
    @endif
@endsection
