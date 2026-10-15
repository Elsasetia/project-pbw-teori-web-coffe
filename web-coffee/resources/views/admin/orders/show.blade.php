@use('App\Enums\OrderStatus')
<x-layouts.admin :title="'Pesanan '.$order->order_code">
    <x-slot:actions>
        <x-button variant="ghost" :href="route('admin.orders.index')">Kembali</x-button>
    </x-slot:actions>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white lg:col-span-2">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-100 text-left text-stone-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Menu</th>
                        <th class="px-4 py-3 text-right font-medium">Harga</th>
                        <th class="px-4 py-3 text-right font-medium">Jumlah</th>
                        <th class="px-4 py-3 text-right font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="px-4 py-3 font-medium text-stone-900">{{ $item->product_name }}</td>
                            <td class="px-4 py-3 text-right tabular-nums"><x-money :value="$item->price" /></td>
                            <td class="px-4 py-3 text-right tabular-nums">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-right tabular-nums"><x-money :value="$item->subtotal" /></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-stone-200">
                        <th colspan="3" class="px-4 py-3 text-right font-medium text-stone-600">Total</th>
                        <td class="px-4 py-3 text-right text-base font-extrabold tabular-nums text-stone-900"><x-money :value="$order->total" /></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="space-y-6">
            <section class="rounded-lg border border-stone-200 bg-white p-6">
                <h2 class="font-bold text-stone-900">Status</h2>

                @if ($order->status === OrderStatus::Cancelled)
                    <p class="mt-3"><x-status-badge :status="$order->status" /></p>
                    <p class="mt-2 text-sm text-stone-600">Pesanan yang dibatalkan tidak bisa diubah lagi. Stok sudah dikembalikan.</p>
                @else
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-3 space-y-3">
                        @csrf
                        @method('PATCH')
                        <x-select name="status" aria-label="Status pesanan">
                            @foreach (OrderStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected($order->status === $status)>{{ $status->label() }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('status')" />
                        <x-button class="w-full">Simpan status</x-button>
                    </form>
                    <p class="mt-3 text-xs text-stone-500">Memilih "Dibatalkan" mengembalikan stok dan tidak bisa dibatalkan lagi.</p>
                @endif
            </section>

            <dl class="space-y-4 rounded-lg border border-stone-200 bg-white p-6 text-sm">
                <div>
                    <dt class="text-stone-500">Pemesan</dt>
                    <dd class="font-medium text-stone-900">{{ $order->customer_name }}</dd>
                    <dd class="text-stone-600">{{ $order->customer_phone }}</dd>
                    @if ($order->user)
                        <dd class="text-stone-600">{{ $order->user->email }}</dd>
                    @endif
                </div>
                <div>
                    <dt class="text-stone-500">Pembayaran</dt>
                    <dd class="font-medium text-stone-900">{{ $order->payment_method->label() }}</dd>
                </div>
                <div>
                    <dt class="text-stone-500">Dibuat</dt>
                    <dd class="text-stone-900">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</dd>
                </div>
                @if ($order->notes)
                    <div>
                        <dt class="text-stone-500">Catatan</dt>
                        <dd class="text-stone-900">{{ $order->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</x-layouts.admin>
