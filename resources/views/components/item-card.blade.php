<a href="#"
    onclick="event.preventDefault(); showItemDetails()"
    class="group block">
    <div class="relative">
        <img src="{{ $item->image_path }}"
            alt="{{ $item->alt_text ?? $item->name }}"
            class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8" />

        @if ($item->status !== 'available')
        <div class="absolute inset-0 z-10 flex items-center justify-center bg-[rgba(255,255,255,0.5)] rounded-lg">
            <span class="text-xl font-semibold">Not Available</span>
        </div>
        @endif
    </div>
    <h3 class="mt-1 text-lg font-medium text-gray-900">{{ $item->name }}</h3>
    <p class="text-md text-gray-700">₱ {{ number_format($item->price, 2) }}</p>
</a>

<x-item-details :item="$item" />