<x-layouts.shop title="Checkout">
    <h1 class="text-2xl font-bold text-stone-900">Checkout</h1>

    <div class="mt-8 grid gap-10 lg:grid-cols-5">
        <form method="POST" action="{{ route('checkout.store') }}" class="space-y-5 lg:col-span-3">
            @csrf

            <x-field label="Nama pemesan" name="customer_name">
                <x-input name="customer_name" :value="auth()->user()->name" required />
            </x-field>

            <x-field label="Nomor telepon" name="customer_phone" hint="Kami hubungi lewat nomor ini kalau pesananmu sudah siap.">
                <x-input name="customer_phone" type="tel" :value="auth()->user()->phone" required />
            </x-field>

            <fieldset>
                <legend class="text-sm font-medium text-stone-700">Metode pembayaran</legend>
                <div class="mt-2 space-y-2">
                    @foreach ($paymentMethods as $method)
                        <label class="flex items-center gap-3 rounded-md border border-stone-200 bg-white px-4 py-3 text-sm">
                            <input type="radio" name="payment_method" value="{{ $method->value }}"
                                   @checked(old('payment_method', 'cash') === $method->value)
                                   class="text-amber-700 focus:ring-amber-700">
                            <span class="font-medium text-stone-900">{{ $method->label() }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('payment_method')" class="mt-1" />
            </fieldset>

            <x-field label="Catatan (opsional)" name="notes">
                <x-textarea name="notes" rows="2" placeholder="Contoh: gula dikurangi" />
            </x-field>

            <x-button class="px-5 py-2.5">Buat pesanan</x-button>
        </form>

        <aside class="h-fit rounded-lg border border-stone-200 bg-white p-6 lg:col-span-2">
            <h2 class="font-bold text-stone-900">Pesananmu</h2>

            <ul class="mt-4 divide-y divide-stone-100 text-sm">
                @foreach ($lines as $line)
                    <li class="flex justify-between gap-4 py-2">
                        <span>{{ $line->quantity }} &times; {{ $line->product->name }}</span>
                        <span class="tabular-nums"><x-money :value="$line->subtotal()" /></span>
                    </li>
                @endforeach
            </ul>

            <dl class="mt-4 flex items-baseline justify-between border-t border-stone-200 pt-4">
                <dt class="text-stone-600">Total</dt>
                <dd class="text-xl font-extrabold tabular-nums text-stone-900"><x-money :value="$total" /></dd>
            </dl>

            <a href="{{ route('cart.index') }}" class="mt-4 inline-block text-sm font-medium text-stone-600 hover:text-stone-900">Ubah isi keranjang</a>
        </aside>
    </div>
</x-layouts.shop>
