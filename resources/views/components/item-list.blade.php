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
        class ProductCard {
            constructor(card) {
                this.card = card;
                this.item = JSON.parse(card.dataset.item);
                this.quantityInput = card.querySelector('.quantity');
                this.stockDisplay = card.querySelector('.stock-display');
                this.priceDisplay = card.querySelector('.variant-price');
                this.actionButtons = card.querySelectorAll('.order-btn');
                this.variantOptions = card.querySelectorAll('.variant-option');
                this.itemInput = card.querySelector('.item-id');
                this.variantInput = card.querySelector('.variant-id');

                this.bindEvents();
                this.updateUI();
            }

            bindEvents() {
                this.variantOptions.forEach(input =>
                    input.addEventListener('change', () => this.updateUI())
                );

                this.card.querySelector('.increment')?.addEventListener('click', () => this.increment());
                this.card.querySelector('.decrement')?.addEventListener('click', () => this.decrement());

                this.actionButtons.forEach(btn =>
                    btn.addEventListener('click', e => this.addToCart(e, btn))
                );

                this.card.querySelector('.open-modal')?.addEventListener('click', e => {
                    e.preventDefault();
                    this.card.querySelector('.modal').classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
                this.card.querySelector('.close-modal')?.addEventListener('click', () => {
                    this.card.querySelector('.modal').classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                });
            }

            updateUI() {
                const selectedAttributes = Object.fromEntries(
                    [...this.variantOptions].filter(i => i.checked)
                    .map(i => [i.name.replace(/attributes\[|\]/g, ''), i.value])
                );

                let match = this.item.variants.find(v =>
                    Object.entries(selectedAttributes).every(([k, val]) => v.attributes[k] === val)
                );

                if (!match && this.item.variants.length === 0) match = {
                    id: null,
                    stock: this.item.stock,
                    price: this.item.price,
                    image_path: this.item.image_path
                };

                if (match) {
                    this.itemInput.value = this.item.id;
                    this.variantInput.value = match.id || '';

                    this.stockDisplay.textContent = `Available: ${match.stock}`;
                    this.quantityInput.max = match.stock;
                    this.priceDisplay && (this.priceDisplay.textContent =
                        `₱ ${parseFloat(match.price).toFixed(2)}`);
                    this.quantityInput.disabled = match.stock <= 0;
                    this.setButtonsEnabled(match.stock > 0);
                } else {
                    this.stockDisplay.textContent = '';
                    this.quantityInput.disabled = true;
                    this.priceDisplay && (this.priceDisplay.textContent = 'Not Available');
                    this.setButtonsEnabled(false);
                }
            }

            increment() {
                if (parseInt(this.quantityInput.value) < parseInt(this.quantityInput.max)) this
                    .quantityInput.value++;
            }

            decrement() {
                if (parseInt(this.quantityInput.value) > parseInt(this.quantityInput.min)) this
                    .quantityInput.value--;
            }

            setButtonsEnabled(enabled) {
                this.actionButtons.forEach(btn => {
                    btn.disabled = !enabled;
                    btn.classList.toggle('opacity-50', !enabled);
                    btn.classList.toggle('cursor-not-allowed', !enabled);
                });
            }

            async addToCart(e, btn) {
                e.preventDefault();

                const itemId = this.itemInput.value;
                const variantId = this.variantInput.value || null;
                const quantity = parseInt(this.quantityInput.value) || 1;

                const payload = {
                    item_id: itemId,
                    variant_id: variantId,
                    quantity
                };

                const route = btn.dataset.route;
                const errorContainer = this.card.querySelector('.order-error');
                errorContainer && (errorContainer.textContent = '');
                btn.disabled = true;

                try {
                    const res = await fetch(route, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();
                    if (!res.ok) throw new Error(data.error || 'Something went wrong');

                    alert('Item added to cart!');
                    this.quantityInput.value = 1;
                } catch (err) {
                    console.error(err);
                    errorContainer && (errorContainer.textContent = err.message || 'Network error');
                } finally {
                    btn.disabled = false;
                }
            }
        }

        document.querySelectorAll('.item-card').forEach(card => new ProductCard(card));
    });
</script>
