<x-layouts.shop title="Menu">
    <x-slot:hero>
        <section class="bg-stone-900 text-stone-100">
            <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 md:grid-cols-2 md:items-center md:py-20">
                <div>
                    <h1 class="text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">
                        Pesan sekarang, ambil saat masih hangat.
                    </h1>
                    <p class="mt-5 max-w-md text-lg text-stone-300">
                        Pilih kopi dan camilan dari rumah, lalu ambil di toko begitu pesananmu siap.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <x-button variant="light" href="#menu" class="px-5 py-2.5">Lihat menu</x-button>
                        <a href="{{ route('feedback.index') }}" class="inline-flex items-center rounded-md border border-stone-600 px-5 py-2.5 text-sm font-semibold text-stone-100 hover:bg-stone-800">Baca masukan pelanggan</a>
                    </div>
                </div>

                {{-- Papan menu: daftar yang terasa seperti papan tulis di balik bar --}}
                <div class="rounded-lg border border-stone-700 bg-stone-800/60 p-6">
                    <h2 class="text-lg font-bold text-white">Baru di bar</h2>

                    @if ($featured->isEmpty())
                        <p class="mt-4 text-stone-400">Menu belum tersedia. Silakan cek lagi nanti.</p>
                    @else
                        <ul class="mt-5 space-y-3">
                            @foreach ($featured as $item)
                                <li class="flex items-baseline gap-2">
                                    <a href="{{ route('products.show', $item->slug) }}" class="font-medium text-stone-50 hover:underline">{{ $item->name }}</a>
                                    <span class="flex-1 border-b border-dotted border-stone-500" aria-hidden="true"></span>
                                    <span class="tabular-nums text-amber-400"><x-money :value="$item->price" /></span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </section>
    </x-slot:hero>

    <section id="menu" class="scroll-mt-20">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h2 class="text-2xl font-bold text-stone-900">Menu</h2>

            <form method="GET" action="{{ route('home') }}#menu" class="flex w-full gap-2 sm:w-auto">
                @if ($activeCategory)
                    <input type="hidden" name="category" value="{{ $activeCategory }}">
                @endif
                <label for="q" class="sr-only">Cari menu</label>
                <input id="q" name="q" type="search" value="{{ $search }}" placeholder="Cari menu"
                       class="w-full rounded-md border-stone-300 text-sm focus:border-amber-700 focus:ring-amber-700 sm:w-64">
                <x-button variant="ghost">Cari</x-button>
            </form>
        </div>

        <nav class="mt-5 flex gap-6 overflow-x-auto border-b border-stone-200 text-sm" aria-label="Kategori menu">
            <a href="{{ route('home', ['q' => $search ?: null]) }}#menu"
               @class([
                   '-mb-px whitespace-nowrap border-b-2 pb-3',
                   'border-amber-700 font-semibold text-stone-900' => ! $activeCategory,
                   'border-transparent text-stone-500 hover:text-stone-900' => $activeCategory,
               ])>Semua</a>

            @foreach ($categories as $category)
                <a href="{{ route('home', ['category' => $category->slug, 'q' => $search ?: null]) }}#menu"
                   @class([
                       '-mb-px whitespace-nowrap border-b-2 pb-3',
                       'border-amber-700 font-semibold text-stone-900' => $activeCategory === $category->slug,
                       'border-transparent text-stone-500 hover:text-stone-900' => $activeCategory !== $category->slug,
                   ])>{{ $category->name }}</a>
            @endforeach
        </nav>

        @if ($products->isEmpty())
            <div class="mt-10 rounded-lg border border-dashed border-stone-300 p-10 text-center">
                <p class="font-semibold text-stone-900">Tidak ada menu yang cocok.</p>
                <p class="mt-1 text-sm text-stone-600">Coba kata kunci lain, atau tampilkan semua kategori.</p>
                <x-button variant="ghost" :href="route('home').'#menu'" class="mt-4">Tampilkan semua menu</x-button>
            </div>
        @else
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-8">{{ $products->links() }}</div>
        @endif
    </section>

    @if ($latestFeedback->isNotEmpty())
        <section class="mt-16 border-t border-stone-200 pt-10">
            <div class="flex items-end justify-between gap-4">
                <h2 class="text-2xl font-bold text-stone-900">Kata pelanggan</h2>
                <a href="{{ route('feedback.index') }}" class="text-sm font-semibold text-amber-800 hover:underline">Lihat semua masukan</a>
            </div>

            <div class="mt-6 grid gap-8 md:grid-cols-3">
                @foreach ($latestFeedback as $item)
                    <figure>
                        <x-stars :rating="$item->rating" />
                        <blockquote class="mt-2 text-stone-700">{{ $item->message }}</blockquote>
                        <figcaption class="mt-3 text-sm font-medium text-stone-900">{{ $item->name }}</figcaption>
                    </figure>
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.shop>
