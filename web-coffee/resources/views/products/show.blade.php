<x-layouts.shop :title="$product->name">
    <a href="{{ route('home') }}#menu" class="text-sm font-medium text-stone-600 hover:text-stone-900">&larr; Kembali ke menu</a>

    <div class="mt-6 grid gap-10 md:grid-cols-2">
        <div class="overflow-hidden rounded-lg border border-stone-200 bg-stone-100">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="aspect-square w-full object-cover">
        </div>

        <div>
            @if ($product->category)
                <a href="{{ route('home', ['category' => $product->category->slug]) }}#menu" class="text-sm font-medium text-amber-800 hover:underline">
                    {{ $product->category->name }}
                </a>
            @endif

            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-stone-900">{{ $product->name }}</h1>
            <p class="mt-3 text-2xl font-bold tabular-nums text-stone-900"><x-money :value="$product->price" /></p>

            @if ($product->description)
                <p class="mt-5 max-w-prose leading-relaxed text-stone-700">{{ $product->description }}</p>
            @endif

            <div class="mt-8 border-t border-stone-200 pt-6">
                @if ($product->stock > 0)
                    <p class="text-sm text-stone-600">
                        @if ($product->stock <= 5)
                            Tinggal {{ $product->stock }} porsi.
                        @else
                            Tersedia.
                        @endif
                    </p>

                    <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-3 flex items-end gap-3">
                        @csrf
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-stone-700">Jumlah</label>
                            <x-input name="quantity" type="number" :value="1" min="1" :max="min($product->stock, 99)" class="mt-1 w-24" />
                        </div>
                        <x-button class="px-5 py-2.5">Tambah ke keranjang</x-button>
                    </form>
                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                @else
                    <p class="font-semibold text-red-700">Menu ini sedang habis.</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.shop>
