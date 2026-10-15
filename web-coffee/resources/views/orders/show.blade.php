<x-layouts.shop :title="'Pesanan '.$order->order_code">
    <a href="{{ route('orders.index') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900">&larr; Semua pesanan</a>

    <div class="mt-4 flex flex-wrap items-center gap-3">
        <h1 class="text-2xl font-bold text-stone-900">{{ $order->order_code }}</h1>
        <x-status-badge :status="$order->status" />
    </div>
    <p class="mt-1 text-sm text-stone-500">Dibuat {{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>

    <div class="mt-8 grid gap-10 lg:grid-cols-3">
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

        <dl class="h-fit space-y-4 rounded-lg border border-stone-200 bg-white p-6 text-sm">
            <div>
                <dt class="text-stone-500">Pemesan</dt>
                <dd class="font-medium text-stone-900">{{ $order->customer_name }}</dd>
                <dd class="text-stone-600">{{ $order->customer_phone }}</dd>
            </div>
            <div>
                <dt class="text-stone-500">Pembayaran</dt>
                <dd class="font-medium text-stone-900">{{ $order->payment_method->label() }}</dd>
            </div>
            @if ($order->notes)
                <div>
                    <dt class="text-stone-500">Catatan</dt>
                    <dd class="text-stone-900">{{ $order->notes }}</dd>
                </div>
            @endif
        </dl>
    </div>
</x-layouts.shop>
