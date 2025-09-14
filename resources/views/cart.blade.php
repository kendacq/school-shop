<html lang="en">

<head>
    @vite('resources/css/app.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cart</title>
</head>

<body>
    <x-nav />
    <div class="container mx-auto p-6">
        @if (!empty($cart->items))
            @foreach ($cart->items as $item)
                <div>
                    <strong>Item Name:</strong> {{ $item['name'] }} <br>
                    <strong>Item ID:</strong> {{ $item['item_id'] }} <br>
                    <strong>Quantity:</strong> {{ $item['quantity'] }} <br>
                    <strong>Unit Price:</strong> ₱ {{ $item['price'] }} <br>
                    <strong>Total Price:</strong> ₱ {{ $item['price'] * $item['quantity'] }} <br>

                    @if ($item['variant_id'])
                        @foreach ($item['attributes'] as $key => $value)
                            <strong>{{ ucwords($key) }}:</strong> {{ $value }} <br>
                        @endforeach
                    @endif
                    <hr>
                    <br>
                </div>
            @endforeach
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>
</body>

</html>
