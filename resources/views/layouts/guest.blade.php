<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 dark:bg-gray-900 antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navbar')
        <main>
            {{ $slot }}
        </main>
    </div>
    @if (request()->is('/'))
        <x-footer></x-footer>
    @endif
    <div x-data="{ show: false, name: '', timeout: null }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4" x-cloak
        x-on:add-to-cart.window="
                    name = $event.detail.name;
                    show = true;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => show = false, 2500);"
        class="fixed bottom-5 right-5 z-50 bg-green-600 text-white px-4 py-2 rounded shadow-md text-sm">
        <span x-text="`1 Item ${name} berhasil ditambahkan ke keranjang.`"></span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('myCartDropdownButton1').click();
        });
    </script>
    @if (session('status') === 'message-sent')
        <script>
            localStorage.removeItem('rentInfo');
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
