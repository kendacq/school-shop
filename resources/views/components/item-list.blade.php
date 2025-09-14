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
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".item-card").forEach(card => {
            const item = JSON.parse(card.dataset.item);
            const modal = document.getElementById(`productModal-${item.id}`);
            const stockDisplay = card.querySelector(".stock-display");
            const quantityInput = card.querySelector(".quantity");
            const priceElement = card.querySelector(".variation-price");
            const imageElement = card.querySelector(".variation-image");
            const actionButtons = card.querySelectorAll(".action-btn");

            function actionButtonsState(state) {
                actionButtons.forEach(btn => {
                    btn.disabled = state;
                    if (state) {
                        btn.classList.add("opacity-50", "cursor-not-allowed");
                    } else {
                        btn.classList.remove("opacity-50", "cursor-not-allowed");
                    }
                });
            }

            card.querySelector(".open-modal").addEventListener("click", e => {
                e.preventDefault();
                modal.classList.remove("hidden");
                document.body.classList.add("overflow-hidden");
            });

            modal.querySelector(".close-modal").addEventListener("click", () => {
                modal.classList.add("hidden");
                document.body.classList.remove("overflow-hidden");
            });

            function updateStockPriceImage() {
                let selected = {};
                card.querySelectorAll(".variation-option:checked").forEach(input => {
                    selected[input.name.replace("attributes[", "").replace("]", "")] = input
                        .value;
                });

                const match = item.variations.find(v =>
                    Object.entries(selected).every(([key, value]) => v.attributes[key] === value)
                );

                if (item.variations.length > 0) {
                    if (match) {
                        if (stockDisplay) {
                            stockDisplay.textContent = "Available: " + match.stock;
                        }
                        if (quantityInput) {
                            quantityInput.disabled = false;
                            quantityInput.max = match.stock;
                            if (parseInt(quantityInput.value) > match.stock) {
                                quantityInput.value = match.stock;
                            }
                        }

                        if (priceElement) {
                            priceElement.textContent = "₱ " + parseFloat(match.price).toFixed(2);
                        }

                        if (imageElement && match.image_path) {
                            imageElement.src = match.image_path;
                        }
                        document.querySelector('input[name="variation_id"]').value = match.id;
                        actionButtonsState(match.stock <= 0);
                    } else {
                        if (stockDisplay) {
                            stockDisplay.textContent = "Not Available";
                        }
                        if (quantityInput) {
                            quantityInput.disabled = true;
                        }
                        if (priceElement) {
                            priceElement.textContent = "Not Available";
                        }
                        if (imageElement) {
                            imageElement.src = item.image_path;
                        }
                        actionButtonsState(true);
                    }
                }
            }

            card.querySelectorAll(".variation-option").forEach(input => {
                input.addEventListener("change", updateStockPriceImage);
            });

            updateStockPriceImage();

            if (card.querySelector(".increment")) {
                card.querySelector(".increment").addEventListener("click", () => {
                    const max = parseInt(quantityInput.max);
                    if (!quantityInput.disabled && parseInt(quantityInput.value) < max) {
                        quantityInput.value = parseInt(quantityInput.value) + 1;
                    }
                });
            }

            if (card.querySelector(".decrement")) {
                card.querySelector(".decrement").addEventListener("click", () => {
                    const min = parseInt(quantityInput.min);
                    if (!quantityInput.disabled && parseInt(quantityInput.value) > min) {
                        quantityInput.value = parseInt(quantityInput.value) - 1;
                    }
                });
            }
        });
    });
</script>
