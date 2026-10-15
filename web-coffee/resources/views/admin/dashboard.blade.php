<x-layouts.admin title="Ringkasan">
    <dl class="grid grid-cols-2 gap-px overflow-hidden rounded-lg border border-stone-200 bg-stone-200 lg:grid-cols-4">
        <div class="bg-white p-5">
            <dt class="text-sm text-stone-500">Pesanan hari ini</dt>
            <dd class="mt-1 text-2xl font-extrabold tabular-nums text-stone-900">{{ $stats['orders_today'] }}</dd>
        </div>
        <div class="bg-white p-5">
            <dt class="text-sm text-stone-500">Pendapatan hari ini</dt>
            <dd class="mt-1 text-2xl font-extrabold tabular-nums text-stone-900"><x-money :value="$stats['revenue_today']" /></dd>
        </div>
        <div class="bg-white p-5">
            <dt class="text-sm text-stone-500">Menunggu diproses</dt>
            <dd class="mt-1 text-2xl font-extrabold tabular-nums text-stone-900">{{ $stats['pending_orders'] }}</dd>
        </div>
        <div class="bg-white p-5">
            <dt class="text-sm text-stone-500">Menu aktif</dt>
            <dd class="mt-1 text-2xl font-extrabold tabular-nums text-stone-900">{{ $stats['active_products'] }}</dd>
        </div>
    </dl>

    <div class="mt-8 grid gap-8 lg:grid-cols-2">
        <section>
            <h2 class="font-bold text-stone-900">Menu terlaris</h2>
            @if ($stats['best_sellers']->isEmpty())
                <p class="mt-3 text-sm text-stone-500">Belum ada penjualan. Angka ini muncul setelah pesanan pertama masuk.</p>
            @else
                <ol class="mt-3 divide-y divide-stone-200 border-y border-stone-200 text-sm">
                    @foreach ($stats['best_sellers'] as $row)
                        <li class="flex justify-between py-2">
                            <span class="text-stone-800">{{ $row->product_name }}</span>
                            <span class="tabular-nums text-stone-600">{{ $row->total_qty }} terjual</span>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>

        <section>
            <h2 class="font-bold text-stone-900">Stok menipis</h2>
            @if ($stats['low_stock']->isEmpty())
                <p class="mt-3 text-sm text-stone-500">Semua menu aktif stoknya masih aman.</p>
            @else
                <ul class="mt-3 divide-y divide-stone-200 border-y border-stone-200 text-sm">
                    @foreach ($stats['low_stock'] as $product)
                        <li class="flex items-center justify-between py-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-stone-800 hover:underline">{{ $product->name }}</a>
                            <span @class([
                                'tabular-nums',
                                'font-semibold text-red-700' => $product->stock === 0,
                                'text-stone-600' => $product->stock > 0,
                            ])>{{ $product->stock === 0 ? 'Habis' : 'Sisa '.$product->stock }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>

    <section class="mt-8">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-stone-900">Pesanan terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-amber-800 hover:underline">Semua pesanan</a>
        </div>

        @if ($stats['recent_orders']->isEmpty())
            <p class="mt-3 text-sm text-stone-500">Belum ada pesanan masuk.</p>
        @else
            <div class="mt-3 overflow-x-auto rounded-lg border border-stone-200 bg-white">
                <table class="min-w-full divide-y divide-stone-200 text-sm">
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($stats['recent_orders'] as $order)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-stone-900">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">{{ $order->order_code }}</a>
                                </td>
                                <td class="px-4 py-3 text-stone-600">{{ $order->customer_name }}</td>
                                <td class="px-4 py-3"><x-status-badge :status="$order->status" /></td>
                                <td class="px-4 py-3 text-right tabular-nums"><x-money :value="$order->total" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-layouts.admin>
