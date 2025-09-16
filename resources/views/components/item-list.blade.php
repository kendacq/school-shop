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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {

                        } else {
                            alert('Item added to cart!');
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

            variantOptions.forEach(opt => opt.addEventListener('change', updateUI));
            card.querySelector(
                '.increment')?.addEventListener('click', increment);
            card.querySelector('.decrement')
                ?.addEventListener('click', decrement);
            openModalBtn?.addEventListener('click',
                openModal);
            closeModalBtn?.addEventListener('click', closeModal);
            addToCartBtn?.addEventListener('click', addToCart);

            updateUI();
        });
    });
</script>
