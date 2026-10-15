@props(['name', 'value' => null, 'rows' => 3])
<textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
    {{ $attributes->merge(['class' => 'w-full rounded-md border-stone-300 text-sm focus:border-amber-700 focus:ring-amber-700']) }}>{{ old($name, $value) }}</textarea>
