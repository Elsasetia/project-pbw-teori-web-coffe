<x-layouts.admin title="Menu">
    <x-slot:actions>
        <x-button :href="route('admin.products.create')">Tambah menu</x-button>
    </x-slot:actions>

    @if ($products->isEmpty())
        <div class="rounded-lg border border-dashed border-stone-300 p-10 text-center">
            <p class="font-semibold text-stone-900">Belum ada menu.</p>
            <p class="mt-1 text-sm text-stone-600">Tambahkan menu pertama supaya pelanggan bisa mulai memesan.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-100 text-left text-stone-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Menu</th>
                        <th class="px-4 py-3 font-medium">Kategori</th>
                        <th class="px-4 py-3 text-right font-medium">Harga</th>
                        <th class="px-4 py-3 text-right font-medium">Stok</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url }}" alt="" class="h-10 w-10 rounded object-cover">
                                    <span class="font-medium text-stone-900">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-stone-600">{{ $product->category?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right tabular-nums"><x-money :value="$product->price" /></td>
                            <td class="px-4 py-3 text-right tabular-nums">{{ $product->stock }}</td>
                            <td class="px-4 py-3">
                                @if ($product->is_active)
                                    <span class="text-emerald-700">Ditampilkan</span>
                                @else
                                    <span class="text-stone-500">Disembunyikan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <x-button variant="ghost" :href="route('admin.products.edit', $product)" class="px-3 py-1.5">Edit</x-button>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                          onsubmit="return confirm('Hapus menu {{ addslashes($product->name) }}? Riwayat pesanan tidak ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="danger" class="px-3 py-1.5">Hapus</x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    @endif
</x-layouts.admin>
