@forelse ($items as $item)
    <tr data-status="{{ $item->status }}"
        class="hover:bg-gray-50 dark:hover:bg-dark-700/50 transition {{ $item->status == 'off' ? 'opacity-70' : '' }}">
        <td class="px-3 py-3 text-center">
            <form action="{{ route('pelanggan.toggle-status', $item->id) }}" method="POST" class="inline">
                @csrf @method('PUT')
                <button type="submit" class="relative inline-flex items-center cursor-pointer">
                    <div
                        class="{{ $item->status == 'on' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }} w-10 h-5 rounded-full transition-colors">
                        <div
                            class="{{ $item->status == 'on' ? 'translate-x-5' : 'translate-x-0.5' }} w-4 h-4 bg-white rounded-full shadow mt-0.5 transition-transform">
                        </div>
                    </div>
                </button>
            </form>
        </td>
        <td class="px-3 py-3">
            <div
                class="font-medium text-gray-800 dark:text-white {{ $item->status == 'off' ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">
                {{ $item->nama }}</div>
            <div class="text-xs text-gray-400 dark:text-gray-500 md:hidden">{{ $item->telepon }}</div>
        </td>
        <td class="px-3 py-3 hidden md:table-cell text-gray-600 dark:text-gray-400">{{ $item->telepon }}</td>
        <td class="px-3 py-3 hidden xl:table-cell">
            <span
                class="px-2 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 rounded-lg text-xs">
                {{ $item->paket_wifi_custom ?? '-' }}
            </span>
        </td>
        <td class="px-3 py-3 hidden xl:table-cell text-right text-gray-600 dark:text-gray-400">
            {{ $item->harga_wifi_custom ? 'Rp ' . number_format($item->harga_wifi_custom, 0, ',', '.') : '-' }}
        </td>
        <td class="px-3 py-3">
            <div class="flex items-center justify-center gap-1 flex-wrap">
                <a href="{{ route('pelanggan.show', $item->id) }}"
                    class="inline-flex items-center justify-center w-8 h-8 bg-gray-50 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 rounded-lg">
                    <i class='bx bx-show text-sm'></i>
                </a>
                <a href="{{ route('pelanggan.tagihan', $item->id) }}"
                    class="inline-flex items-center justify-center w-8 h-8 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg">
                    <i class='bx bx-receipt text-sm'></i>
                </a>
                <a href="{{ route('invoice.create') }}?pelanggan_id={{ $item->id }}"
                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition"
                    title="Buat Tagihan Baru">
                    <i class='bx bx-plus-circle text-sm'></i>
                </a>
                <button
                    onclick='openEditModal("{{ $item->id }}", "mitra", "{{ addslashes($item->nama) }}", "{{ $item->telepon }}", "{{ addslashes($item->alamat) }}", "", "", "", "", "{{ addslashes($item->paket_wifi_custom) }}", "{{ $item->harga_wifi_custom }}")'
                    class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg">
                    <i class='bx bx-edit text-sm'></i>
                </button>
                <form id="deleteForm{{ $item->id }}" action="{{ route('pelanggan.destroy', $item->id) }}"
                    method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        onclick="confirmDelete('deleteForm{{ $item->id }}', '{{ addslashes($item->nama) }}')"
                        class="inline-flex items-center justify-center w-8 h-8 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-lg transition"
                        title="Hapus">
                        <i class='bx bx-trash text-sm'></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-3 py-8 text-center text-gray-400 dark:text-gray-500">
            <i class='bx bx-store-alt text-3xl block mb-2'></i>
            Tidak ada data mitra
        </td>
    </tr>
@endforelse
