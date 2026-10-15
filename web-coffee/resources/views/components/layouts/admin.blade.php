@props(['title'])
@php
    // [nama route, pola route aktif, label]
    $links = [
        ['admin.dashboard', 'admin.dashboard', 'Ringkasan'],
        ['admin.products.index', 'admin.products.*', 'Menu'],
        ['admin.categories.index', 'admin.categories.*', 'Kategori'],
        ['admin.orders.index', 'admin.orders.*', 'Pesanan'],
        ['admin.feedback.index', 'admin.feedback.*', 'Masukan'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} · Admin {{ config('app.name', 'Web Coffee') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>[x-cloak] { display: none !important; } body { font-family: 'Bricolage Grotesque', ui-sans-serif, system-ui, sans-serif; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-stone-800 antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        <aside class="bg-stone-900 text-stone-300 md:w-56 md:shrink-0">
            <div class="flex items-center justify-between px-4 py-4 md:block">
                <p class="text-lg font-extrabold text-white">{{ config('app.name', 'Web Coffee') }}</p>
                <p class="text-xs text-stone-400">Panel admin</p>
            </div>

            <nav class="flex gap-1 overflow-x-auto px-2 pb-3 md:flex-col md:px-3" aria-label="Menu admin">
                @foreach ($links as [$route, $pattern, $label])
                    <a href="{{ route($route) }}"
                       @class([
                           'whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium',
                           'bg-stone-700 text-white' => request()->routeIs($pattern),
                           'hover:bg-stone-800' => ! request()->routeIs($pattern),
                       ])>{{ $label }}</a>
                @endforeach

                <a href="{{ route('home') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium hover:bg-stone-800 md:mt-4">Lihat toko</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full whitespace-nowrap rounded-md px-3 py-2 text-left text-sm font-medium hover:bg-stone-800">Keluar</button>
                </form>
            </nav>
        </aside>

        <div class="min-w-0 flex-1 p-4 md:p-8">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-2xl font-bold text-stone-900">{{ $title }}</h1>
                @isset($actions)
                    <div>{{ $actions }}</div>
                @endisset
            </div>

            <x-flash />
            {{ $slot }}
        </div>
    </div>
</body>
</html>
