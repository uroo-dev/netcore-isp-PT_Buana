@forelse($data as $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
            {{ $data->firstItem() + $loop->index }}</td>
        <td class="px-4 py-3 hidden sm:table-cell text-gray-600 dark:text-gray-400">
            {{ $item->tanggal->format('d M Y') }}</td>
        <td class="px-4 py-3">
            <span
                class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-900/40 text-gray-700 dark:text-blue-400 px-2.5 py-1 rounded-lg text-xs font-medium">
                <i class='bx bx-category text-xs'></i> {{ ucfirst($item->kategori) }}
            </span>
            <!-- Info mobile: tanggal -->
            <div class="sm:hidden text-xs text-gray-400 dark:text-gray-500 mt-1">
                {{ $item->tanggal->format('d M Y') }}</div>
        </td>
        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">
            {{ $item->keterangan }}</td>
        <td class="px-4 py-3 text-right font-semibold text-red-600 dark:text-red-400">
            <span class="money-value"
                data-amount="Rp {{ number_format($item->jumlah, 0, ',', '.') }}">Rp
                {{ number_format($item->jumlah, 0, ',', '.') }}</span>
        </td>
        <td class="px-4 py-3 hidden lg:table-cell text-gray-500 dark:text-gray-400 text-xs">
            {{ $item->user?->nama_lengkap ?? '-' }}</td>
        <td class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-1.5">
                <button data-modal-target="modalEdit{{ $item->id }}"
                    data-modal-toggle="modalEdit{{ $item->id }}"
                    class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 rounded-lg transition"
                    title="Edit">
                    <i class='bx bx-edit text-sm'></i>
                </button>
                <form id="deleteForm{{ $item->id }}"
                    action="{{ route('biaya-operasional.destroy', $item->id) }}" method="POST"
                    class="inline">
                    @csrf @method('DELETE')
                    <button type="button"
                        onclick="confirmDelete('deleteForm{{ $item->id }}')"
                        class="inline-flex items-center justify-center w-8 h-8 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-lg transition"
                        title="Hapus">
                        <i class='bx bx-trash text-sm'></i>
                    </button>
                </form>

                <!-- Modal Edit Biaya Operasional -->
                <div id="modalEdit{{ $item->id }}" tabindex="-1"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class='bx bx-edit-alt text-amber-600 text-xl'></i>
                                Edit Biaya Operasional
                            </h3>
                            <button data-modal-hide="modalEdit{{ $item->id }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
                        </div>
                        <form action="{{ route('biaya-operasional.update', $item->id) }}" method="POST"
                            class="space-y-4" id="editForm{{ $item->id }}">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Tanggal</label>
                                    <input type="date" name="tanggal"
                                        value="{{ $item->tanggal->format('Y-m-d') }}" required
                                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                </div>
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Kategori</label>
                                    <select name="kategori" required
                                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                        <option value="listrik" {{ $item->kategori == 'listrik' ? 'selected' : '' }}>⚡ Listrik
                                        </option>
                                        <option value="sewa" {{ $item->kategori == 'sewa' ? 'selected' : '' }}>🏢 Sewa</option>
                                        <option value="gaji" {{ $item->kategori == 'gaji' ? 'selected' : '' }}>💰 Gaji</option>
                                        <option value="transport" {{ $item->kategori == 'transport' ? 'selected' : '' }}>🚗
                                            Transport</option>
                                        <option value="konsumsi" {{ $item->kategori == 'konsumsi' ? 'selected' : '' }}>🍽️
                                            Konsumsi</option>
                                        <option value="umum" {{ $item->kategori == 'umum' ? 'selected' : '' }}>📋 Umum</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Keterangan</label>
                                <input type="text" name="keterangan" value="{{ $item->keterangan }}" required
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Jumlah
                                    (Rp)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="text" class="edit-jumlah-input w-full pl-8 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-right text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                                        value="{{ number_format($item->jumlah, 0, ',', '.') }}"
                                        placeholder="0">
                                    <input type="hidden" name="jumlah" class="edit-jumlah-raw"
                                        value="{{ $item->jumlah }}">
                                </div>
                            </div>
                            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-dark-700">
                                <button type="button" data-modal-hide="modalEdit{{ $item->id }}"
                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-700 hover:bg-gray-50 dark:hover:bg-dark-700 rounded-xl transition">Batal</button>
                                <button type="submit"
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition flex items-center gap-2">
                                    <i class='bx bx-save'></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
            <i class='bx bx-category text-4xl mb-2 block'></i>
            <p>Belum ada data kas perusahaan</p>
            <button data-modal-target="modalTambah" data-modal-toggle="modalTambah"
                class="mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline">
                Tambah biaya operasional pertama →
            </button>
        </td>
    </tr>
@endforelse