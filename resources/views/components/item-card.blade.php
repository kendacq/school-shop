    @php
        $frontendItem = [
            'id' => $item->id,
            'name' => $item->name,
            'sku' => $item->sku,
            'price' => $item->price,
            'stock' => $item->stock,
            'image_path' => $item->image_path,
            'note' => $item->note,
            'category' => $item->category?->name,
            'book' => $item->book,
            'variants' => $item->variants->map(
                fn($v) => [
                    'id' => $v->id,
                    'sku' => $v->sku,
                    'attributes' => $v->attributes,
                    'price' => $v->price,
                    'stock' => $v->stock,
                    'image_path' => $v->image_path,
                    'alt_text' => $v->alt_text,
                ],
            ),
        ];
    @endphp

    <div class="item-card" data-item='@json($frontendItem)'>
        <a href="#" class="group block mt-6 open-modal">
            <div class="relative">
                <img src="{{ $item->image_path }}" alt="{{ $item->alt_text ?? $item->name }}"
                    class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8" />
                @if (!$item->status)
                    <div
                        class="absolute inset-0 z-10 flex items-center justify-center bg-[rgba(255,255,255,0.5)] rounded-lg">
                        <span class="text-xl font-semibold">Not Available</span>
                    </div>
                @endif
            </div>
            <h3 class="mt-1 text-lg font-medium text-gray-900">{{ $item->name }}</h3>
            <p class="text-md text-gray-700">₱ {{ number_format($item->price, 2) }}</p>
        </a>

        <div class="hidden modal fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4">
            <div class="modal bg-white rounded-lg shadow-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">
                <button class="close-modal text-xl font-bold absolute top-4 right-4">✕</button>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex justify-center">
                        <img src="{{ $item->image_path }}" alt="{{ $item->alt_text ?? $item->name }}"
                            class="w-full max-w-xs h-auto object-contain">
                    </div>
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-bold">{{ $item->name }}</h3>
                        <h3 class="text-xs mt-1"><b>SKU: </b>{{ $item->sku }}</h3>
                        @if ($item->note)
                            <p class="text-gray-600 text-sm mt-2">{{ $item->note }}</p>
                        @endif

                        @if (stripos($item->category->name, 'book') !== false)
                            <p class="text-sm mt-2">{{ $item->book?->description }}</p>
                            <div class="grid grid-rows-7 sm:grid-rows-4 grid-flow-col gap-1 mt-2 text-sm">
                                <h3><b>ID: </b>{{ $item->book?->book_id }}</h3>
                                <h3><b>Edition: </b>{{ $item->book?->edition }}</h3>
                                <h3><b>Volume: </b>{{ $item->book?->volume }}</h3>
                                <h3><b>Pages: </b>{{ $item->book?->pages }}</h3>
                                <h3><b>Author: </b>{{ $item->book?->author }}</h3>
                                <h3><b>Publisher: </b>{{ $item->book?->publisher }}</h3>
                                <h3><b>Publish Date: </b>{{ $item->book?->publish_date?->format('Y-m-d') }}</h3>
                            </div>
                        @endif

                        <div class="flex flex-col gap-2">
                            @if ($item->variants->isNotEmpty())
                                <p class="price text-2xl font-bold"></p>
                                <span class="text-xs text-gray-600 italic">*Price may vary based on selected
                                    variant</span>
                            @else
                                <p class="price text-2xl font-bold">₱ {{ number_format($item->price, 2) }}</p>
                            @endif
                        </div>

                        @php
                            $attributes = [];
                            foreach ($item->variants as $variant) {
                                foreach ($variant->attributes as $key => $value) {
                                    $attributes[$key][] = $value;
                                }
                            }
                            foreach ($attributes as $key => $values) {
                                $attributes[$key] = array_unique($values);
                            }
                        @endphp
                        @foreach ($attributes as $type => $values)
                            <div class="mb-4">
                                <strong>{{ ucfirst($type) }}</strong>
                                <div class="flex gap-3 mt-1">
                                    @foreach ($values as $index => $value)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attributes[{{ $type }}]"
                                                value="{{ $value }}" class="hidden peer variant-option"
                                                data-attribute="{{ $type }}"
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

                        @auth
                            <div class="flex mt-2 flex-wrap items-center gap-2">
                                <div class="flex items-center space-x-2">
                                    <label class="font-semibold" for="quantity-{{ $item->id }}">Quantity</label>
                                    <button type="button"
                                        class="decrement action-btn w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 text-xl font-bold">-</button>
                                    <input type="number" id="quantity-{{ $item->id }}" name="quantity" value="1"
                                        min="1" max="{{ $item->stock }}"
                                        class="quantity w-16 text-center border rounded-md [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-moz-appearance]:textfield">
                                    <button type="button"
                                        class="increment action-btn w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 text-xl font-bold">+</button>
                                </div>
                                <div>
                                    <span class="stock-display">Available: {{ $item->stock ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="order-error text-red-600 text-sm space-y-1 text-end"></div>
                            <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2">
                                <button
                                    class="cart-btn action-btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto"
                                    data-route="{{ route('cart.store') }}">
                                    Add to cart
                                </button>
                                <button
                                    class="order-btn action-btn bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 w-full sm:w-auto"
                                    data-route="">
                                    Order
                                </button>
                            </div>
                        @else
                            <div class="flex mt-2 flex-wrap items-center gap-2">
                                <div>
                                    <span class="stock-display">Available: {{ $item->stock ?? '-' }}</span>
                                </div>
                            </div>
                            <p class="text-center m-4">Log in to Order</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
