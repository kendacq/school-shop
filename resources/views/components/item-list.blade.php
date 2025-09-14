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

            card.querySelector(".open-modal").addEventListener("click", e => {
                e.preventDefault();
                modal.classList.remove("hidden");
                document.body.classList.add("overflow-hidden");
            });

            modal.querySelector(".close-modal").addEventListener("click", () => {
                modal.classList.add("hidden");
                document.body.classList.remove("overflow-hidden");
            });

            function updateStock() {
                let selected = {};
                card.querySelectorAll(".variation-option:checked").forEach(input => {
                    selected[input.name.replace("attributes[", "").replace("]", "")] = input
                        .value;
                });

                const match = item.variations.find(v =>
                    Object.entries(selected).every(([key, value]) => v.attributes[key] === value)
                );

                if (match) {
                    stockDisplay.textContent = "In Stock: " + match.stock;
                    quantityInput.max = match.stock;
                    if (parseInt(quantityInput.value) > match.stock) {
                        quantityInput.value = match.stock;
                    }
                }
            }

            card.querySelectorAll(".variation-option").forEach(input => {
                input.addEventListener("change", updateStock);
            });

            updateStock();

            card.querySelector(".increment").addEventListener("click", () => {
                const max = parseInt(quantityInput.max);
                if (parseInt(quantityInput.value) < max) quantityInput.value = parseInt(
                    quantityInput.value) + 1;
            });
            card.querySelector(".decrement").addEventListener("click", () => {
                const min = parseInt(quantityInput.min);
                if (parseInt(quantityInput.value) > min) quantityInput.value = parseInt(
                    quantityInput.value) - 1;
            });
        });
    });
</script>
