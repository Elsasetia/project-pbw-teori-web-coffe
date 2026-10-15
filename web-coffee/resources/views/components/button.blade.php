@props(['variant' => 'primary', 'href' => null])
@php
    $classes = match ($variant) {
        'ghost' => 'border border-stone-300 bg-white text-stone-700 hover:bg-stone-100',
        'danger' => 'border border-red-200 bg-white text-red-700 hover:bg-red-50',
        'light' => 'bg-stone-100 text-stone-900 hover:bg-white',
        default => 'bg-amber-700 text-white hover:bg-amber-800',
    };
    $base = 'inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-600 focus-visible:ring-offset-2 disabled:opacity-50';
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$base $classes"]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => "$base $classes"]) }}>{{ $slot }}</button>
@endif
