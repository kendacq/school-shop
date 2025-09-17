@extends('layouts.app')

@section('title', 'Cart')

@section('content')
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
                            showToast('Error', data.error, 'error');
                        } else {
                            cartItem.remove();
                            showToast('Success', item.name + ' removed from cart', 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function showToast(
                title = '',
                message = '',
                type = 'info',
                duration = 4000
            ) {
                const container = document.getElementById('toast-container');

                const toast = document.createElement('div');
                toast.className =
                    `max-w-sm w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black/5 border-l-4 p-4 flex items-start space-x-3 transform transition-all duration-300 ease-out opacity-0 translate-y-2`;

                const colors = {
                    success: 'border-green-500',
                    error: 'border-red-500',
                    warning: 'border-yellow-500',
                    info: 'border-blue-500'
                };
                toast.classList.add(colors[type] || colors.info);

                const icons = {
                    success: `<svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`,
                    error: `<svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`,
                    warning: `<svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4M12 17h.01"/></svg>`,
                    info: `<svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>`
                };

                toast.innerHTML =
                    `<div class="flex-shrink-0">${icons[type] || icons.info}</div><div class="flex-1"><p class="text-sm font-medium text-gray-900 dark:text-gray-100">${title}</p><p class="mt-1 text-sm text-gray-600 dark:text-gray-300">${message}</p></div>`;

                container.appendChild(toast);

                requestAnimationFrame(() => {
                    toast.classList.remove('opacity-0', 'translate-y-2');
                    toast.classList.add('opacity-100', 'translate-y-0');
                });

                setTimeout(() => {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => toast.remove(), 200);
                }, duration);
            }

            quantityInput.addEventListener('input', updateItemQty);
            deleteBtn.addEventListener('click', deleteItem);
        });
    });
</script>
