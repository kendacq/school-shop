<a href="#"
    onclick="event.preventDefault(); openModal('productModal-{{ $item->id }}')"
    class="group block">
    <div class="relative">
        <img src="{{ $item->image_path }}"
            alt="{{ $item->alt_text ?? $item->name }}"
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

<!-- Modal -->
<div id="productModal-{{ $item->id }}"
    class="hidden fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-[800px] max-w-full p-6 relative">
        <button onclick="closeModal('productModal-{{ $item->id }}')" class="text-xl font-bold">
            ✕
        </button>
        <div class="grid grid-cols-2 gap-6">
            <div class="flex justify-center">
                <img src="{{ $item->image_path }}"
                    alt="{{ $item->alt_text ?? $item->name }}"
                    class="w-64 h-auto object-contain">
            </div>
            <div>
                <h3 class="text-2xl font-bold">{{ $item->name }}</h3>
                <h3 class="text-xs"><b>SKU: </b>{{ $item->sku }}</h3>
                @if ($item->note)
                <p class="text-gray-600 text-sm mt-2">
                    {{ $item->note }}
                </p>
                @endif
                @if (strcasecmp($item->category?->name, 'book') === 0)
                    Book
                @endif
                <form>
                    @foreach ($item->variations->groupBy('type') as $type => $variations)
                    <div class="mt-4">
                        <h4 class="font-semibold">{{ \Illuminate\Support\Str::title($type) }}</h4>
                        <div class="flex space-x-2 mt-2">
                            @foreach ($variations as $i => $variation)
                            <label>
                                <input type="radio"
                                    name="variation_{{ \Illuminate\Support\Str::slug($type) }}_{{ $item->id }}"
                                    value="{{ $variation->id }}"
                                    class="hidden peer"
                                    @checked($i===0)>
                                <span class="border px-3 py-1 rounded cursor-pointer peer-checked:bg-green-600 peer-checked:text-white hover:bg-gray-200">
                                    {{ $variation->name }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-4">
                        <label class="font-semibold" for="quantity-{{ $item->id }}">Quantity</label>
                        <div class="flex items-center space-x-3 mt-2">
                            <input type="number"
                                id="quantity-{{ $item->id }}"
                                value="1"
                                min="1"
                                max="10"
                                class="w-12 text-center border">
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <p class="text-2xl font-bold">₱ {{ number_format($item->price, 2) }}</p>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-2">
                        <button type="submit" name="action" value="reserve" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                            Reserve
                        </button>
                        <button type="submit" name="action" value="cart" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Add to cart
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>