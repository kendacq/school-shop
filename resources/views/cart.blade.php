@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    <x-nav />
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Shopping Cart</h1>

            <form class="mt-12 grid grid-cols-1 lg:grid-cols-12 lg:gap-x-12 xl:gap-x-16" method="POST" action="">
                @csrf

                <!-- Cart Items -->
                <section aria-labelledby="cart-heading" class="lg:col-span-7">
                    <h2 id="cart-heading" class="sr-only">Items in your shopping cart</h2>

                    @if (!empty($cart->items))
                        <ul role="list" class="divide-y divide-gray-200 border-b border-t border-gray-200">
                            @foreach ($cart->items as $item)
                                <li class="flex py-6 sm:py-10 cart-item bg-white" data-item='@json($item)'>
                                    <div class="flex-shrink-0">
                                        <img src="" alt=""
                                            class="h-24 w-24 rounded-md object-cover object-center sm:h-48 sm:w-48">
                                    </div>

                                    <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                                        <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-700">
                                                    {{ $item['name'] }}
                                                </h3>

                                                @if ($item['variant_id'])
                                                    <div class="mt-1 text-sm text-gray-500">
                                                        @foreach ($item['attributes'] as $key => $value)
                                                            <p><strong>{{ ucwords($key) }}:</strong> {{ $value }}</p>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <p class="mt-1 text-sm font-medium text-gray-900">₱
                                                    {{ number_format($item['price'], 2) }}</p>
                                            </div>

                                            <div class="mt-4 sm:mt-0 sm:pr-9">
                                                <label class="sr-only">Quantity</label>
                                                <input type="number" value="{{ $item['quantity'] }}" min="1"
                                                    max="{{ $item['stock'] }}"
                                                    class="quantity-input block w-20 rounded-md border border-gray-300 py-1.5 text-base leading-5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                                                    data-route="{{ route('cart.update') }}" />
                                                <p class="mt-1 text-sm text-gray-500"><strong>Available:</strong> {{ $item['stock'] }}</p>
                                                <div class="absolute right-0 top-0">
                                                    <button type="button"
                                                        class="delete-btn inline-flex -m-2 p-2 text-red-600 hover:text-red-800"
                                                        data-route="{{ route('cart.destroy') }}">
                                                        <span class="sr-only">Remove</span>
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M6.28 5.22a.75.75 0 011.06 0L10 7.94l2.72-2.72a.75.75 0 111.06 1.06L11.06 9l2.72 2.72a.75.75 0 01-1.06 1.06L10 10.06l-2.72 2.72a.75.75 0 01-1.06-1.06L8.94 9 6.22 6.28a.75.75 0 010-1.06z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="mt-4 text-sm font-medium text-gray-900">
                                            Subtotal: ₱
                                            <span>{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-gray-500">Your cart is empty.</p>
                    @endif
                </section>

                <!-- Order Summary -->
                <section aria-labelledby="summary-heading"
                    class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8">
                    <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Order summary</h2>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-medium text-gray-900">Order total</dt>
                            <dd class="order-total text-base font-medium text-gray-900">₱
                                {{ number_format(collect($cart->items)->sum(fn($item) => $item['price'] * $item['quantity']), 2) }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Checkout
                        </button>
                    </div>
                </section>
            </form>
        </div>
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.cart-item').forEach(cartItem => {
            const item = JSON.parse(cartItem.dataset.item);
            const quantityInput = cartItem.querySelector('.quantity-input');
            const deleteBtn = cartItem.querySelector('.delete-btn');

            function updateItemQty() {
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
                            showToast('Error', data.error, 'error');
                        } else {
                            updateCartTotal();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function deleteItem() {
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
                            updateCartTotal();
                            showToast('Success', item.name + ' removed from cart', 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function updateCartTotal() {
                let total = 0;
                document.querySelectorAll('.cart-item').forEach(cartItem => {
                    const item = JSON.parse(cartItem.dataset.item);
                    const qty = parseInt(cartItem.querySelector('.quantity-input')?.value || 0,
                        10);
                    total += item.price * qty;
                });
                document.querySelector('.order-total').textContent = '₱ ' + total.toFixed(2);
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
