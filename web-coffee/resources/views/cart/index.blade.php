<x-layouts.shop title="Keranjang">
    <h1 class="text-2xl font-bold text-stone-900">Keranjang</h1>

    @if ($lines->isEmpty())
        <div class="mt-8 rounded-lg border border-dashed border-stone-300 p-10 text-center">
            <p class="font-semibold text-stone-900">Keranjangmu masih kosong.</p>
            <p class="mt-1 text-sm text-stone-600">Pilih kopi atau camilan dari menu untuk mulai memesan.</p>
            <x-button :href="route('home').'#menu'" class="mt-4">Lihat menu</x-button>
        </div>
    @else
        <div class="mt-8 grid gap-10 lg:grid-cols-3">
            <ul class="divide-y divide-stone-200 border-y border-stone-200 lg:col-span-2">
                @foreach ($lines as $line)
                    <li class="flex flex-wrap items-center gap-4 py-5">
                        <img src="{{ $line->product->image_url }}" alt="" class="h-20 w-20 rounded-md object-cover">

                        <div class="min-w-0 flex-1">
                            <a href="{{ route('products.show', $line->product->slug) }}" class="font-semibold text-stone-900 hover:underline">{{ $line->product->name }}</a>
                            <p class="text-sm tabular-nums text-stone-500"><x-money :value="$line->product->price" /> per porsi</p>
                        </div>

                        <form method="POST" action="{{ route('cart.update', $line->product) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <label for="qty-{{ $line->product->id }}" class="sr-only">Jumlah {{ $line->product->name }}</label>
                            <input id="qty-{{ $line->product->id }}" type="number" name="quantity" value="{{ $line->quantity }}"
                                   min="1" max="{{ min($line->product->stock, 99) }}"
                                   class="w-20 rounded-md border-stone-300 text-sm focus:border-amber-700 focus:ring-amber-700">
                            <x-button variant="ghost" class="px-3 py-1.5">Ubah</x-button>
                        </form>

                        <p class="w-28 text-right font-semibold tabular-nums text-stone-900"><x-money :value="$line->subtotal()" /></p>

                        <form method="POST" action="{{ route('cart.destroy', $line->product) }}">
                            @csrf
                            @method('DELETE')
                            <x-button variant="danger" class="px-3 py-1.5">Hapus</x-button>
                        </form>
                    </li>
                @endforeach
            </ul>

            <aside class="h-fit rounded-lg border border-stone-200 bg-white p-6">
                <h2 class="font-bold text-stone-900">Ringkasan</h2>
                <dl class="mt-4 flex items-baseline justify-between">
                    <dt class="text-stone-600">Total</dt>
                    <dd class="text-xl font-extrabold tabular-nums text-stone-900"><x-money :value="$total" /></dd>
                </dl>
                <x-button :href="route('checkout.create')" class="mt-6 w-full py-2.5">Lanjut ke checkout</x-button>
                @guest
                    <p class="mt-3 text-sm text-stone-500">Kamu akan diminta masuk atau daftar dulu.</p>
                @endguest
            </aside>
        </div>
    @endif
</x-layouts.shop>
