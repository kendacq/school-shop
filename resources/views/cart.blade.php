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
                <div id="cart-item-{{ $item['item_id'] }}">
                    <strong>Item Name:</strong> {{ $item['name'] }} <br>
                    <strong>Item ID:</strong> {{ $item['item_id'] }} <br>

                    <label for="quantity-{{ $item['item_id'] }}"><strong>Quantity:</strong></label>
                    <input 
                        type="number" 
                        id="quantity-{{ $item['item_id'] }}" 
                        value="{{ $item['quantity'] }}" 
                        min="1" 
                        class="w-20 border rounded p-1"
                        oninput="updateCartQuantity('{{ route('cart.update', $item['item_id']) }}', this)"
                    >
                    <span id="error-{{ $item['item_id'] }}" class="text-red-600 text-sm"></span>
                    <br>

                    <strong>Unit Price:</strong> ₱ {{ $item['price'] }} <br>
                    <strong>Total Price:</strong> ₱ <span id="total-{{ $item['item_id'] }}">{{ $item['price'] * $item['quantity'] }}</span> <br>

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

    <script>
        async function updateCartQuantity(url, input) {
            const errorEl = document.getElementById('error-' + input.id.split('-')[1]);
            const totalEl = document.getElementById('total-' + input.id.split('-')[1]);

            errorEl.textContent = '';

            try {
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quantity: input.value })
                });

                const data = await response.json();

                if (!response.ok) {
                    errorEl.textContent = data.message || 'Error updating cart.';
                } else {
                    totalEl.textContent = data.total_price;
                }
            } catch (e) {
                errorEl.textContent = 'Network error. Please try again.';
            }
        }
    </script>