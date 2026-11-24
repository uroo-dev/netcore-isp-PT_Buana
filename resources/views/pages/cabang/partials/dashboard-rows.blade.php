@forelse($produkCabang as $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $produkCabang->firstItem() + $loop->index }}</td>
        <td class="px-4 py-3">
            <div class="font-medium text-gray-800 dark:text-white">{{ $item->produk->nama }}</div>
            <div class="text-xs text-gray-400 dark:text-gray-500">
                @if($item->produk->kategori)
                <span class="bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded text-xs">{{ $item->produk->kategori }}</span>
                @endif
            </div>
        </td>
        <td class="px-4 py-3 text-center">
            <span class="font-semibold {{ $item->stok == 0 ? 'text-red-600 dark:text-red-400' : ($item->stok <= 10 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-800 dark:text-white') }}">
                {{ $item->stok }}
            </span>
        </td>
        <td class="px-4 py-3 text-center hidden sm:table-cell">
            @if($item->stok > 10)
                <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full text-xs font-medium">Tersedia</span>
            @elseif($item->stok > 0)
                <span class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-400 px-2.5 py-1 rounded-full text-xs font-medium">Menipis</span>
            @else
                <span class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2.5 py-1 rounded-full text-xs font-medium">Habis</span>
            @endif
        </td>
        <td class="px-4 py-3 text-center">
            @if($item->stok > 0)
            <button data-modal-target="modalPakai{{ $item->id }}" data-modal-toggle="modalPakai{{ $item->id }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-xs font-medium transition">
                <i class='bx bx-check-double'></i> Pakai
            </button>
            @else
            <span class="text-gray-400 dark:text-gray-500 text-xs">Stok habis</span>
            @endif
        </td>
    </tr>

    <!-- Modal Pakai -->
    <div id="modalPakai{{ $item->id }}" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 w-full max-w-sm rounded-2xl shadow-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    <i class='bx bx-check-double text-gray-600 mr-2'></i>
                    Pakai: {{ $item->produk->nama }}
                </h3>
                <button data-modal-hide="modalPakai{{ $item->id }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <form action="{{ route('cabang.produk.pakai', $item->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Jumlah</label>
                    <input type="number" name="jumlah" min="1" max="{{ $item->stok }}" required
                        data-harga="{{ $item->produk->harga_beli }}"
                        oninput="previewBiaya({{ $item->id }})"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Stok tersedia: {{ $item->stok }}</p>
                    <p id="biayaPreview{{ $item->id }}" class="text-xs font-medium mt-1 {{ $item->produk->harga_beli > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}">
                        @if($item->produk->harga_beli > 0)
                            Kas keluar: Rp {{ number_format($item->produk->harga_beli, 0, ',', '.') }} / pcs
                        @else
                            Harga beli belum diisi (biaya tidak tercatat di kas)
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Keterangan <span class="text-gray-400">(opsional)</span></label>
                    <input type="text" name="keterangan" placeholder="Misal: Dipakai untuk instalasi"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-hide="modalPakai{{ $item->id }}"
                        class="px-4 py-2 bg-gray-200 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm">Pakai</button>
                </div>
            </form>
        </div>
    </div>
@empty
    <tr>
        <td colspan="5" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
            <i class='bx bxs-package text-4xl mb-2 block'></i>
            <p>Belum ada produk di cabang ini</p>
            <p class="text-xs mt-1">Hubungi admin untuk memindahkan produk ke cabang</p>
        </td>
    </tr>
@endforelse