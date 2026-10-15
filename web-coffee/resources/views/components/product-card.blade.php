@props(['product'])
<article class="flex flex-col overflow-hidden rounded-lg border border-stone-200 bg-white">
    <a href="{{ route('products.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-stone-100">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover">
    </a>

    <div class="flex flex-1 flex-col p-4">
        <p class="text-sm text-stone-500">{{ $product->category?->name ?? 'Lainnya' }}</p>
        <h3 class="mt-0.5 text-base font-semibold text-stone-900">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:underline">{{ $product->name }}</a>
        </h3>

        <div class="mt-auto flex items-center justify-between gap-3 pt-4">
            <span class="font-bold tabular-nums text-stone-900"><x-money :value="$product->price" /></span>

            @if ($product->stock > 0)
                <form method="POST" action="{{ route('cart.store', $product) }}">
                    @csrf
                    <x-button class="px-3 py-1.5">Tambah</x-button>
                </form>
            @else
                <span class="text-sm font-medium text-red-700">Habis</span>
            @endif
        </div>
    </div>
</article>
