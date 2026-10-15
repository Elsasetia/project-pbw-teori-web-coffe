<x-layouts.admin title="Kategori">
    <div class="grid gap-8 lg:grid-cols-3">
        <section class="h-fit rounded-lg border border-stone-200 bg-white p-6">
            <h2 class="font-bold text-stone-900">Tambah kategori</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-4 space-y-4">
                @csrf
                <x-field label="Nama kategori" name="name">
                    <x-input name="name" required />
                </x-field>
                <x-button class="w-full">Simpan kategori</x-button>
            </form>
        </section>

        <section class="lg:col-span-2">
            @if ($categories->isEmpty())
                <div class="rounded-lg border border-dashed border-stone-300 p-10 text-center">
                    <p class="font-semibold text-stone-900">Belum ada kategori.</p>
                    <p class="mt-1 text-sm text-stone-600">Kategori membantu pelanggan menyaring menu, misalnya "Kopi dingin".</p>
                </div>
            @else
                <ul class="divide-y divide-stone-200 rounded-lg border border-stone-200 bg-white">
                    @foreach ($categories as $category)
                        <li x-data="{ editing: false }" class="flex flex-wrap items-center gap-3 px-4 py-3">
                            <div x-show="!editing" class="min-w-0 flex-1">
                                <p class="font-medium text-stone-900">{{ $category->name }}</p>
                                <p class="text-sm text-stone-500">{{ $category->products_count }} menu</p>
                            </div>

                            <form x-show="editing" x-cloak method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex flex-1 gap-2">
                                @csrf
                                @method('PATCH')
                                <label for="cat-{{ $category->id }}" class="sr-only">Nama kategori</label>
                                <input id="cat-{{ $category->id }}" name="name" value="{{ $category->name }}" required
                                       class="w-full rounded-md border-stone-300 text-sm focus:border-amber-700 focus:ring-amber-700">
                                <x-button>Simpan</x-button>
                                <x-button type="button" variant="ghost" x-on:click="editing = false">Batal</x-button>
                            </form>

                            <div x-show="!editing" class="flex gap-2">
                                <x-button type="button" variant="ghost" class="px-3 py-1.5" x-on:click="editing = true">Ubah nama</x-button>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Hapus kategori ini? Menu di dalamnya tetap ada, tapi jadi tanpa kategori.')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" class="px-3 py-1.5">Hapus</x-button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-layouts.admin>
