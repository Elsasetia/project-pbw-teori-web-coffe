@props(['label', 'name', 'hint' => null])
<div {{ $attributes }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-stone-700">{{ $label }}</label>
    <div class="mt-1">{{ $slot }}</div>
    @if ($hint)
        <p class="mt-1 text-xs text-stone-500">{{ $hint }}</p>
    @endif
    <x-input-error :messages="$errors->get($name)" class="mt-1" />
</div>
