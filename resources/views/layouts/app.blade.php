<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/css/app.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My App')</title>
</head>

<body>
    <div id="toast-container" class="fixed bottom-4 right-4 z-55 space-y-3"></div>
    @yield('content')

</body>

</html>
