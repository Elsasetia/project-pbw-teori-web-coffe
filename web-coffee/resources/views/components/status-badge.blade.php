@props(['status'])
@php
    // Kelas ditulis lengkap di sini (bukan di enum) supaya terbaca oleh Tailwind saat build.
    $classes = match ($status) {
        \App\Enums\OrderStatus::Pending => 'bg-amber-100 text-amber-900',
        \App\Enums\OrderStatus::Paid => 'bg-sky-100 text-sky-900',
        \App\Enums\OrderStatus::Completed => 'bg-emerald-100 text-emerald-900',
        \App\Enums\OrderStatus::Cancelled => 'bg-stone-200 text-stone-700',
    };
@endphp
<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium $classes"]) }}>{{ $status->label() }}</span>
