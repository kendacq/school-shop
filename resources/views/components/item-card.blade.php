<a href="#" onclick="event.preventDefault(); openModal('productModal-{{ $item->id }}')" class="group block mt-6">
    <div class="relative">
        <img src="{{ $item->image_path }}" alt="{{ $item->alt_text ?? $item->name }}"
            class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8" />

        @if (!$item->status)
            <div class="absolute inset-0 z-10 flex items-center justify-center bg-[rgba(255,255,255,0.5)] rounded-lg">
                <span class="text-xl font-semibold">Not Available</span>
            </div>
        @endif
    </div>
    <h3 class="mt-1 text-lg font-medium text-gray-900">{{ $item->name }}</h3>
    <p class="text-md text-gray-700">₱ {{ number_format($item->price, 2) }}</p>
</a>

<div id="productModal-{{ $item->id }}"
    class="hidden modal fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4">
    <div class="modal bg-white rounded-lg shadow-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">
        <button onclick="closeModal('productModal-{{ $item->id }}')"
            class="text-xl font-bold absolute top-4 right-4">
            ✕
        </button>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="flex justify-center">
                <img src="{{ $item->image_path }}" alt="{{ $item->alt_text ?? $item->name }}"
                    class="w-full max-w-xs h-auto object-contain">
            </div>
            <div>
                <h3 class="text-2xl sm:text-3xl font-bold">{{ $item->name }}</h3>
                <h3 class="text-xs mt-1"><b>SKU: </b>{{ $item->sku }}</h3>
                @if ($item->note || $item->note !== '')
                    <p class="text-gray-600 text-sm mt-2">
                        {{ $item->note }}
                    </p>
                @endif

                @if (strcasecmp($item->category?->name, 'books' || strcasecmp($item->category?->name, 'book')) === 0)
                    <p class="text-s mt-2">{{ $item->book?->description }}</p>
                    <div class="grid grid-rows-7 sm:grid-rows-4 grid-flow-col gap-1 mt-2 text-s">
                        <h3><b>ID: </b>{{ $item->book?->book_id }}</h3>
                        <h3><b>Edition: </b>{{ $item->book?->edition }}</h3>
                        <h3><b>Volume: </b>{{ $item->book?->volume }}</h3>
                        <h3><b>Pages: </b>{{ $item->book?->pages }}</h3>
                        <h3><b>Author: </b>{{ $item->book?->author }}</h3>
                        <h3><b>Publisher: </b>{{ $item->book?->publisher }}</h3>
                        <h3><b>Publish Date: </b>{{ $item->book?->publish_date->format('Y-m-d') }}</h3>
                    </div>
                @endif

                <form>
                    @php
                        $attributes = [];

                        foreach ($item->variations as $variation) {
                            foreach ($variation->attributes as $key => $value) {
                                $attributes[$key][] = $value;
                            }
                        }

                        foreach ($attributes as $key => $values) {
                            $attributes[$key] = array_unique($values);
                        }
                    @endphp

                    @foreach ($attributes as $type => $values)
                        <div class="mb-4">
                            <strong>{{ ucfirst($type) }}</strong>:
                            <div class="flex gap-3 mt-2">
                                @foreach ($values as $index => $value)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attributes[{{ $type }}]"
                                            value="{{ $value }}" class="hidden peer variation-option"
                                            @if ($index === 0) checked @endif>
                                        <span
                                            class="px-3 py-1 border rounded peer-checked:bg-blue-500 peer-checked:text-white">
                                            {{ $value }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-4">
                        <span id="stock-display">In Stock: {{ $item->stock ?? '-' }}</span>
                    </div>


                    <div class="mt-2">
                        <label class="font-semibold" for="quantity-{{ $item->id }}">Quantity</label>
                        <div class="flex items-center space-x-2 mt-2">
                            <button type="button" onclick="decrementQuantity('quantity-{{ $item->id }}')"
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 text-xl font-bold">
                                -
                            </button>
                            <input type="number" id="quantity-{{ $item->id }}" name="quantity" value="1"
                                min="1" max="{{ $item->stock }}"
                                class="w-16 text-center border rounded-md
                                [&::-webkit-inner-spin-button]:appearance-none
                                [&::-webkit-outer-spin-button]:appearance-none
                                [&::-moz-appearance]:textfield">
                            <button type="button" onclick="incrementQuantity('quantity-{{ $item->id }}')"
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 text-xl font-bold">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="mt-2 flex items-center gap-4">
                        <p class="text-2xl font-bold">₱ {{ number_format($item->price, 2) }}</p>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2">
                        <button type="submit" name="action" value="reserve"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 w-full sm:w-auto">
                            Reserve
                        </button>
                        <button type="submit" name="action" value="cart"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto">
                            Add to cart
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .modal {
        touch-action: pan-x pan-y;
    }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>

<script>
    function incrementQuantity(id) {
        const input = document.getElementById(id);
        const max = parseInt(input.max);
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }

    function decrementQuantity(id) {
        const input = document.getElementById(id);
        const min = parseInt(input.min);
        if (parseInt(input.value) > min) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const variations = @json($item->variations);
        const stockDisplay = document.getElementById("stock-display");
        const quantity = document.getElementById("quantity-{{ $item->id }}");

        function updateStock() {
            let selected = {};
            document.querySelectorAll(".variation-option:checked").forEach(input => {
                selected[input.name.replace("attributes[", "").replace("]", "")] = input.value;
            });

            let match = variations.find(v => {
                return Object.entries(selected).every(([key, value]) => v.attributes[key] === value);
            });

            if (match) {
                stockDisplay.textContent = "In Stock: " + match.stock;
                quantity.max = match.stock;
                if (parseInt(quantity.value) > match.stock) {
                    quantity.value = match.stock;
                }
            }
        }

        document.querySelectorAll(".variation-option").forEach(input => {
            input.addEventListener("change", updateStock);
        });

        updateStock();
    });
</script>
