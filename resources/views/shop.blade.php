<html lang="en">

<head>
    @vite('resources/css/app.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Shop</title>
</head>

<body>
    <x-nav />
    <br />

    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <form id="filterForm" class="mb-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <input type="text" name="search" id="searchInput"
                    value="{{ request('search') }}"
                    placeholder="Search name or ISBN"
                    class="border border-gray-300 rounded px-4 py-2 w-full" />
                <select name="category"
                    class="border border-gray-300 rounded px-4 py-2 w-full"
                    onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ ucfirst($category) }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
            @forelse ($items as $item)
            <x-item-card :item="$item" />
            @empty
            <p class="col-span-full text-center text-gray-500">No items found.</p>
            @endforelse
        </div>
    </div>
</body>

</html>

<script>
    const form = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.querySelector('select[name="category"]');
    const itemsList = document.getElementById('itemsList');

    function debounce(func, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function fetchFilteredItems() {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        fetch(`/?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            itemsList.innerHTML = html;
        });
    }

    const debouncedFetch = debounce(fetchFilteredItems, 400);

    searchInput.addEventListener('input', debouncedFetch);
    categorySelect.addEventListener('change', fetchFilteredItems);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        fetchFilteredItems();
    });
</script>