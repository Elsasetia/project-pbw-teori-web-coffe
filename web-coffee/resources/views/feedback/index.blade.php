<x-layouts.shop title="Masukan">
    <div class="grid gap-12 lg:grid-cols-5">
        <section class="lg:col-span-3">
            <h1 class="text-2xl font-bold text-stone-900">Masukan pelanggan</h1>

            @if ($total > 0)
                <p class="mt-2 text-stone-600">
                    Rata-rata {{ number_format($average, 1, ',', '.') }} dari 5, dari {{ $total }} masukan.
                </p>
            @endif

            @if ($feedback->isEmpty())
                <div class="mt-8 rounded-lg border border-dashed border-stone-300 p-10 text-center">
                    <p class="font-semibold text-stone-900">Belum ada masukan.</p>
                    <p class="mt-1 text-sm text-stone-600">Jadilah yang pertama menulis lewat formulir di samping.</p>
                </div>
            @else
                <ul class="mt-8 divide-y divide-stone-200 border-y border-stone-200">
                    @foreach ($feedback as $item)
                        <li class="py-5">
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-semibold text-stone-900">{{ $item->name }}</p>
                                <x-stars :rating="$item->rating" />
                            </div>
                            <p class="mt-2 text-stone-700">{{ $item->message }}</p>
                            <p class="mt-2 text-xs text-stone-500">{{ $item->created_at->translatedFormat('d F Y') }}</p>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6">{{ $feedback->links() }}</div>
            @endif
        </section>

        <section class="h-fit rounded-lg border border-stone-200 bg-white p-6 lg:col-span-2">
            <h2 class="font-bold text-stone-900">Tulis masukan</h2>

            <form method="POST" action="{{ route('feedback.store') }}" class="mt-4 space-y-4">
                @csrf

                <x-field label="Nama" name="name">
                    <x-input name="name" :value="auth()->user()?->name" required />
                </x-field>

                <x-field label="Penilaian" name="rating">
                    <x-select name="rating" required>
                        @foreach ([5 => 'Sangat baik', 4 => 'Baik', 3 => 'Cukup', 2 => 'Kurang', 1 => 'Buruk'] as $value => $label)
                            <option value="{{ $value }}" @selected((int) old('rating', 5) === $value)>{{ $value }} - {{ $label }}</option>
                        @endforeach
                    </x-select>
                </x-field>

                <x-field label="Pesan" name="message">
                    <x-textarea name="message" rows="4" required />
                </x-field>

                <x-button class="w-full py-2.5">Kirim masukan</x-button>
            </form>
        </section>
    </div>
</x-layouts.shop>
