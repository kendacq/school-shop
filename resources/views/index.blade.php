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
    <div class="container mx-auto p-6">
        <form id="filterForm" class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <input type="text" name="search" id="searchInput"
                    value="{{ request('search') }}"
                    placeholder="Search Name, SKU, or ISBN"
                    class="border border-gray-300 rounded px-4 py-2 w-full">

                <select name="category" id="categorySelect"
                    class="border border-gray-300 rounded px-4 py-2 w-full">
                    <option value="">All Categories</option>
                    @foreach($categories as $id => $name)
                    <option value="{{ $id }}" {{ request('category') == $id ? 'selected' : '' }}>
                        {{ ucfirst($name) }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
        <div id="items-container">
            @include('components.item-list', ['items' => $items, 'categories' => $categories])
        </div>
    </div>
</body>

</html>

<script>
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');

    function fetchItems() {
        const search = searchInput.value;
        const category = categorySelect.value;

        fetch(`/items?search=${search}&category=${category}`)
            .then(res => res.text())
            .then(html => {
                document.getElementById('items-container').innerHTML = html;
            });
    }

    searchInput.addEventListener('input', fetchItems);
    categorySelect.addEventListener('change', fetchItems);
</script>