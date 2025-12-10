<!DOCTYPE html>
<html>
<head>
    <title>Cart App</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-8">
    <nav class="mb-4 flex justify-between">
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('cart.show') }}">Cart</a>
    </nav>
    @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif
    @yield('content')
</div>
</body>
</html>
