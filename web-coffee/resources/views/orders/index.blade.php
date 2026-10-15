<x-layouts.shop title="Pesanan saya">
    <h1 class="text-2xl font-bold text-stone-900">Pesanan saya</h1>

    @if ($orders->isEmpty())
        <div class="mt-8 rounded-lg border border-dashed border-stone-300 p-10 text-center">
            <p class="font-semibold text-stone-900">Kamu belum punya pesanan.</p>
            <p class="mt-1 text-sm text-stone-600">Pesanan yang kamu buat akan muncul di sini.</p>
            <x-button :href="route('home').'#menu'" class="mt-4">Lihat menu</x-button>
        </div>
    @else
        <div class="mt-8 overflow-x-auto rounded-lg border border-stone-200 bg-white">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-100 text-left text-stone-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Kode</th>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Total</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-stone-900">{{ $order->order_code }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-4 py-3"><x-status-badge :status="$order->status" /></td>
                            <td class="px-4 py-3 text-right tabular-nums"><x-money :value="$order->total" /></td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('orders.show', $order->order_code) }}" class="font-semibold text-amber-800 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</x-layouts.shop>
