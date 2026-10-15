@props(['name'])
<select id="{{ $name }}" name="{{ $name }}"
    {{ $attributes->merge(['class' => 'w-full rounded-md border-stone-300 text-sm focus:border-amber-700 focus:ring-amber-700']) }}>
    {{ $slot }}
</select>
