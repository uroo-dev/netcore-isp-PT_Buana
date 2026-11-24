@forelse($produk as $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 font-medium">
            {{ $produk->firstItem() + $loop->index }}
        </td>
        <td class="px-4 py-3">
            <div class="font-medium text-gray-800 dark:text-white">{{ $item->nama }}</div>
            <!-- Info mobile: kategori -->
            <div class="sm:hidden mt-1">
                @if ($item->kategori)
                    <span
                        class="bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded text-xs">{{ $item->kategori }}</span>
                @endif
            </div>
        </td>
        <td class="px-4 py-3 hidden sm:table-cell">
            @if ($item->kategori)
                <span
                    class="bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-1 rounded-lg text-xs font-medium">{{ $item->kategori }}</span>
            @else
                <span class="text-gray-400 dark:text-gray-500">-</span>
            @endif
        </td>
        <td class="px-4 py-3 text-right hidden md:table-cell text-gray-600 dark:text-gray-400">
            Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
        </td>
        <td class="px-4 py-3 text-right font-medium text-gray-800 dark:text-white">
            Rp {{ number_format($item->harga, 0, ',', '.') }}
        </td>
        <td class="px-4 py-3 text-center">
            <span
                class="font-semibold {{ $item->stok <= 10 && $item->stok > 0 ? 'text-yellow-600 dark:text-yellow-400' : ($item->stok == 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-white') }}">
                {{ $item->stok }}
            </span>
        </td>
        <td class="px-4 py-3 text-center hidden lg:table-cell">
            @if ($item->stok > 10)
                <span
                    class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full text-xs font-medium">
                    <i class='bx bx-check-circle text-xs'></i> Tersedia
                </span>
            @elseif($item->stok > 0)
                <span
                    class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-400 px-2.5 py-1 rounded-full text-xs font-medium">
                    <i class='bx bx-error text-xs'></i> Menipis
                </span>
            @else
                <span
                    class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2.5 py-1 rounded-full text-xs font-medium">
                    <i class='bx bx-x-circle text-xs'></i> Habis
                </span>
            @endif
        </td>
        <td class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                <!-- Tombol Edit -->
                <button data-modal-target="modalEdit{{ $item->id }}"
                    data-modal-toggle="modalEdit{{ $item->id }}"
                    class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 hover:bg-gray-200 dark:hover:bg-gray-900/50 rounded-lg transition"
                    title="Edit">
                    <i class='bx bx-edit text-sm'></i>
                </button>

                <!-- Tombol Restok -->
                <button data-modal-target="modalRestok{{ $item->id }}"
                    data-modal-toggle="modalRestok{{ $item->id }}"
                    class="inline-flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 rounded-lg transition"
                    title="Restok">
                    <i class='bx bx-plus-circle text-sm'></i>
                </button>

                <!-- Tombol Hapus -->
                <form action="{{ route('produk.destroy', $item->id) }}" method="POST"
                    class="inline" id="delete-form-{{ $item->id }}">
                    @csrf @method('DELETE')
                    <button type="button"
                        onclick="confirmDelete('delete-form-{{ $item->id }}')"
                        class="inline-flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition"
                        title="Hapus">
                        <i class='bx bx-trash text-sm'></i>
                    </button>
                </form>

                <!-- Modal Edit Produk -->
                <div id="modalEdit{{ $item->id }}" tabindex="-1"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div
                        class="bg-white dark:bg-dark-800 w-full max-w-md rounded-2xl shadow-xl p-6 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit
                                Produk</h3>
                            <button data-modal-hide="modalEdit{{ $item->id }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
                        </div>
                        <form action="{{ route('produk.update', $item->id) }}" method="POST"
                            class="space-y-4">
                            @csrf @method('PUT')
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nama
                                    Produk</label>
                                <input type="text" name="nama" value="{{ $item->nama }}"
                                    required
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                            </div>
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Kategori</label>
                                <input type="text" name="kategori"
                                    value="{{ $item->kategori ?? '' }}" list="kategoriList"
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500"
                                    placeholder="Opsional">
                            </div>
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Stok</label>
                                <input type="number" name="stok" value="{{ $item->stok }}"
                                    min="0" required
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                            </div>
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Harga
                                    Beli</label>
                                <input type="number" name="harga_beli"
                                    value="{{ $item->harga_beli }}" min="0"
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                            </div>
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Harga
                                    Jual</label>
                                <input type="number" name="harga" value="{{ $item->harga }}"
                                    required min="0"
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button"
                                    data-modal-hide="modalEdit{{ $item->id }}"
                                    class="px-4 py-2 bg-gray-200 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm hover:bg-gray-300 dark:hover:bg-dark-600 transition">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm transition">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Restok Produk -->
                <div id="modalRestok{{ $item->id }}" tabindex="-1"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div
                        class="bg-white dark:bg-dark-800 w-full max-w-md rounded-2xl shadow-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                <i class='bx bx-plus-circle text-green-600 mr-2'></i>
                                Restok: {{ $item->nama }}
                            </h3>
                            <button data-modal-hide="modalRestok{{ $item->id }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
                        </div>
                        <form action="{{ route('produk.restok', $item->id) }}" method="POST"
                            class="space-y-4">
                            @csrf @method('PUT')
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Jumlah
                                    Tambahan Stok</label>
                                <input type="number" name="stok"
                                    id="stok{{ $item->id }}" min="1" required
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500"
                                    oninput="hitungBiaya({{ $item->id }}, {{ $item->harga_beli ?? 0 }})">
                            </div>
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Total
                                    Biaya (Rp)</label>
                                <input type="number" name="total_biaya"
                                    id="totalBiaya{{ $item->id }}" min="0" required
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500"
                                    placeholder="Otomatis dari harga beli, bisa diedit">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    Otomatis: harga beli (Rp
                                    {{ number_format($item->harga_beli ?? 0, 0, ',', '.') }}) ×
                                    stok
                                </p>
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button"
                                    data-modal-hide="modalRestok{{ $item->id }}"
                                    class="px-4 py-2 bg-gray-200 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm hover:bg-gray-300 dark:hover:bg-dark-600 transition">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm transition">Restok</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
            <i class='bx bx-package text-4xl mb-2 block'></i>
            <p>Tidak ada produk</p>
            <button data-modal-target="modalProduk" data-modal-toggle="modalProduk"
                class="mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline">
                Tambah produk pertama →
            </button>
        </td>
    </tr>
@endforelse