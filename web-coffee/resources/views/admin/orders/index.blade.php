@use('App\Enums\OrderStatus')
<x-layouts.admin title="Pesanan">
    <nav class="mb-6 flex gap-5 overflow-x-auto border-b border-stone-200 text-sm" aria-label="Filter status">
        <a href="{{ route('admin.orders.index') }}"
           @class([
               '-mb-px whitespace-nowrap border-b-2 pb-3',
               'border-amber-700 font-semibold text-stone-900' => ! $activeStatus,
               'border-transparent text-stone-500 hover:text-stone-900' => $activeStatus,
           ])>Semua</a>
        @foreach (OrderStatus::cases() as $status)
            <a href="{{ route('admin.orders.index', ['status' => $status->value]) }}"
               @class([
                   '-mb-px whitespace-nowrap border-b-2 pb-3',
                   'border-amber-700 font-semibold text-stone-900' => $activeStatus === $status,
                   'border-transparent text-stone-500 hover:text-stone-900' => $activeStatus !== $status,
               ])>{{ $status->label() }}</a>
        @endforeach
    </nav>

    @if ($orders->isEmpty())
        <div class="rounded-lg border border-dashed border-stone-300 p-10 text-center">
            <p class="font-semibold text-stone-900">Tidak ada pesanan di sini.</p>
            <p class="mt-1 text-sm text-stone-600">Pesanan dari pelanggan akan muncul otomatis.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-100 text-left text-stone-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Kode</th>
                        <th class="px-4 py-3 font-medium">Pemesan</th>
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
                            <td class="px-4 py-3 text-stone-700">{{ $order->customer_name }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-4 py-3"><x-status-badge :status="$order->status" /></td>
                            <td class="px-4 py-3 text-right tabular-nums"><x-money :value="$order->total" /></td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-amber-800 hover:underline">Buka</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</x-layouts.admin>
