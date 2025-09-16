@extends('layouts.app')

@section('title', 'Cart')

@section('content')

    <body>
        <x-nav />
        <div class="container mx-auto p-6">
            @if (!empty($cart->items))
                @foreach ($cart->items as $item)
                    <div class="cart-item bg-white shadow-md rounded-lg p-4 mb-6" data-item='@json($item)'>
                        <p class="error-display text-red-600 text-sm mb-2"></p>
                        <h2 class="text-lg font-semibold mb-2">{{ $item['name'] }}</h2>
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <label class="font-medium">Quantity:</label>
                            <input type="number" value="{{ $item['quantity'] }}" min="1"
                                class="quantity-input w-20 border rounded p-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                data-route="{{ route('cart.update') }}">
                        </div>
                        <p class="mb-2"><strong>Unit Price:</strong> ₱ {{ $item['price'] }}</p>
                        <p class="total-price mb-2"><strong>Total Price:</strong> ₱
                            <span>{{ $item['price'] * $item['quantity'] }}</span>
                        </p>
                        @if ($item['variant_id'])
                            <div class="mb-2">
                                @foreach ($item['attributes'] as $key => $value)
                                    <p><strong>{{ ucwords($key) }}:</strong> {{ $value }}</p>
                                @endforeach
                            </div>
                        @endif
                        <button class="delete-btn bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                            data-route="{{ route('cart.destroy') }}">Remove</button>
                        <hr class="my-4 border-gray-300">
                    </div>
                @endforeach
            @else
                <p class="text-center text-gray-500">Your cart is empty.</p>
            @endif
        </div>

    </body>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.cart-item').forEach(cartItem => {
            const item = JSON.parse(cartItem.dataset.item);
            const quantityInput = cartItem.querySelector('.quantity-input');
            const deleteBtn = cartItem.querySelector('.delete-btn');
            const errorText = cartItem.querySelector('.error-display');
            const totalPrice = cartItem.querySelector('.total-price');

            function updateItemQty() {
                errorText.textContent = '';
                let payload = {
                    item_id: item.item_id,
                    quantity: quantityInput.value
                };

                if (item.variant_id) {
                    payload.variant_id = item.variant_id;
                }

                fetch(quantityInput.dataset.route, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            errorText.textContent = data.error;
                        } else {
                            quantityInput.setAttribute('max', data.stock);
                            totalPrice.querySelector('span').textContent = data.total;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function deleteItem() {
                errorText.textContent = '';
                let payload = {
                    item_id: item.item_id,
                };

                if (item.variant_id) {
                    payload.variant_id = item.variant_id;
                }

                fetch(deleteBtn.dataset.route, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            errorText.textContent = data.error;
                        } else {
                            cartItem.remove();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            quantityInput.addEventListener('input', updateItemQty);
            deleteBtn.addEventListener('click', deleteItem);
        });
    });
</script>
