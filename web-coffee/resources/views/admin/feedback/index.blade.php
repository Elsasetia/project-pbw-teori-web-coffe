<x-layouts.admin title="Masukan">
    @if ($feedback->isEmpty())
        <div class="rounded-lg border border-dashed border-stone-300 p-10 text-center">
            <p class="font-semibold text-stone-900">Belum ada masukan.</p>
            <p class="mt-1 text-sm text-stone-600">Masukan dari halaman publik akan muncul di sini.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-100 text-left text-stone-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Pengirim</th>
                        <th class="px-4 py-3 font-medium">Penilaian</th>
                        <th class="px-4 py-3 font-medium">Pesan</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 align-top">
                    @foreach ($feedback as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium text-stone-900">{{ $item->name }}</p>
                                <p class="text-xs text-stone-500">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3"><x-stars :rating="$item->rating" /></td>
                            <td class="max-w-md px-4 py-3 text-stone-700">{{ $item->message }}</td>
                            <td class="px-4 py-3">
                                @if ($item->is_approved)
                                    <span class="text-emerald-700">Ditampilkan</span>
                                @else
                                    <span class="text-stone-500">Disembunyikan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.feedback.update', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-button variant="ghost" class="px-3 py-1.5">{{ $item->is_approved ? 'Sembunyikan' : 'Tampilkan' }}</x-button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.feedback.destroy', $item) }}"
                                          onsubmit="return confirm('Hapus masukan ini secara permanen?')">
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

        <div class="mt-6">{{ $feedback->links() }}</div>
    @endif
</x-layouts.admin>
