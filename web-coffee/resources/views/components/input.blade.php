@props(['name', 'type' => 'text', 'value' => null])
<input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
    {{ $attributes->merge(['class' => 'w-full rounded-md border-stone-300 text-sm focus:border-amber-700 focus:ring-amber-700']) }}>
