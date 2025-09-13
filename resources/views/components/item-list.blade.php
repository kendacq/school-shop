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
