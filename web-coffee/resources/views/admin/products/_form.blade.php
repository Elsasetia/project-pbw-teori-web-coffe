@php($p = $product ?? null)

<form method="POST"
      action="{{ $p ? route('admin.products.update', $p) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      class="max-w-2xl space-y-5">
    @csrf
    @if ($p)
        @method('PUT')
    @endif

    <x-field label="Nama menu" name="name">
        <x-input name="name" :value="$p?->name" required />
    </x-field>

    <x-field label="Kategori" name="category_id">
        <x-select name="category_id">
            <option value="">Tanpa kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $p?->category_id) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </x-select>
    </x-field>

    <x-field label="Deskripsi" name="description">
        <x-textarea name="description" :value="$p?->description" rows="3" />
    </x-field>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-field label="Harga (Rupiah)" name="price">
            <x-input name="price" type="number" min="0" step="500" :value="$p?->price" required />
        </x-field>

        <x-field label="Stok" name="stock">
            <x-input name="stock" type="number" min="0" :value="$p?->stock ?? 0" required />
        </x-field>
    </div>

    <x-field label="Gambar" name="image" hint="JPG, PNG, atau WebP, maksimal 2 MB.">
        @if ($p?->image)
            <img src="{{ $p->image_url }}" alt="Gambar saat ini" class="mb-3 h-24 w-24 rounded-md object-cover">
        @endif
        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp"
               class="block w-full text-sm text-stone-600 file:mr-4 file:rounded-md file:border-0 file:bg-stone-200 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-stone-800 hover:file:bg-stone-300">
    </x-field>

    <label class="flex items-center gap-3 text-sm text-stone-800">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $p?->is_active ?? true))
               class="rounded border-stone-300 text-amber-700 focus:ring-amber-700">
        Tampilkan di menu pelanggan
    </label>

    <div class="flex gap-3">
        <x-button class="px-5 py-2.5">Simpan menu</x-button>
        <x-button variant="ghost" :href="route('admin.products.index')" class="px-5 py-2.5">Batal</x-button>
    </div>
</form>
