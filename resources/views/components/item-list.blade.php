<div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
    @forelse ($items as $item)
        <x-item-card :item="$item" />
    @empty
        <p class="col-span-full text-center text-gray-500">No items found.</p>
    @endforelse
</div>

<div class="mt-4">
    {{ $items->links() }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.item-card').forEach(card => {
            const item = JSON.parse(card.dataset.item);
            const modal = card.querySelector('.modal');
            const quantityInput = card.querySelector('.quantity');
            const stockDisplay = card.querySelector('.stock-display');
            const priceDisplay = card.querySelector('.price');
            const variantOptions = card.querySelectorAll('.variant-option');
            const actionBtns = card.querySelectorAll('.action-btn');
            const openModalBtn = card.querySelector('.open-modal');
            const closeModalBtn = card.querySelector('.close-modal');
            const addToCartBtn = card.querySelector('.cart-btn');
            const variantExists = variantOptions.length > 0;

            console.log(item);

            function updateUI() {
                const variant = getVariant();

                if (variantExists) {
                    if (variant) {
                        priceDisplay.textContent = `₱ ${parseFloat(variant.price).toFixed(2)}`;
                        if (variant.stock !== undefined) {
                            stockDisplay.textContent = `Available: ${variant.stock}`;
                        } else {
                            stockDisplay.textContent = "Available: -";
                        }
                        if (quantityInput !== null) {
                            quantityInput.classList.remove('opacity-50', 'cursor-not-allowed');
                            quantityInput.max = variant.stock;
                            quantityInput.disabled = variant.stock <= 0;
                        }
                        actionBtns.forEach(btn => {
                            btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            btn.disabled = false;
                        });
                    } else {
                        priceDisplay.textContent = "Not Available";
                        if (quantityInput !== null) {
                            quantityInput.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                        stockDisplay.textContent = "Available: -";
                        actionBtns.forEach(btn => {
                            btn.classList.add('opacity-50', 'cursor-not-allowed');
                            btn.disabled = true;
                        });
                    }
                }
            }

            function increment() {
                const current = parseInt(quantityInput.value) || 1;
                const max = parseInt(quantityInput.max) || 1;
                if (current < max) {
                    quantityInput.value = current + 1;
                }
            }

            function decrement() {
                const current = parseInt(quantityInput.value) || 1;
                const min = parseInt(quantityInput.min) || 1;
                if (current > min) {
                    quantityInput.value = current - 1;
                }
            }

            function openModal(e) {
                e.preventDefault();
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            function addToCart() {
                let payload = {
                    item_id: item.id,
                    quantity: quantityInput.value
                };

                if (variantExists) {
                    payload.variant_id = getVariant().id;
                }

                console.log(payload);

                fetch(addToCartBtn.dataset.route, {
                        method: 'POST',
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
                            showToast('Success', item.name+" added to cart!", 'success');
                            closeModal();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function getVariant() {
                const selectedAttributes = {};
                variantOptions.forEach(opt => {
                    if (opt.checked) {
                        const attrName = opt.dataset.attribute;
                        selectedAttributes[attrName] = opt.value;
                    }
                });

                return variant = item.variants.find(v => {
                    return Object.entries(selectedAttributes).every(([attr, value]) => {
                        return v.attributes[attr] === value;
                    });
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

            variantOptions.forEach(opt => opt.addEventListener('change', updateUI));
            card.querySelector('.increment')?.addEventListener('click', increment);
            card.querySelector('.decrement')?.addEventListener('click', decrement);
            openModalBtn?.addEventListener('click', openModal);
            closeModalBtn?.addEventListener('click', closeModal);
            addToCartBtn?.addEventListener('click', addToCart);

            updateUI();
        });
    });
</script>
