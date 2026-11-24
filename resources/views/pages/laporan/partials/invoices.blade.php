<div
    class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class='bx bx-receipt text-gray-600 text-xl'></i>
            Detail Tagihan
            <span
                class="text-xs bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded-full">{{ $invoices->total() }}</span>
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead
                class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                <tr>
                    <th class="px-4 py-3 text-left">No Nota</th>
                    <th class="px-4 py-3 text-left hidden sm:table-cell">Pelanggan</th>
                    <th class="px-4 py-3 text-center hidden md:table-cell">Tanggal</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-dark-700">
                @forelse($invoices as $inv)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                        <td class="px-4 py-3">
                            <a href="{{ route('invoice.show', $inv->id) }}"
                                class="font-mono text-xs font-semibold text-gray-600 dark:text-blue-400 hover:underline">
                                {{ $inv->kode_invoice }}
                            </a>
                            <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $inv->pelanggan->nama }}</div>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell text-gray-700 dark:text-gray-300">
                            {{ $inv->pelanggan->nama }}</td>
                        <td class="px-4 py-3 text-center hidden md:table-cell text-gray-600 dark:text-gray-400">
                            {{ $inv->tanggalJ }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800 dark:text-white">
                            <span class="money-value"
                                data-amount="Rp {{ number_format($inv->total, 0, ',', '.') }}">Rp
                                {{ number_format($inv->total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($inv->status === 'lunas')
                                <span
                                    class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full text-xs font-medium">
                                    <i class='bx bx-check-circle text-xs'></i> Lunas
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-400 px-2.5 py-1 rounded-full text-xs font-medium">
                                    <i class='bx bx-time text-xs'></i> Cicilan
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class='bx bx-receipt text-3xl mb-2 block'></i>
                            Tidak ada data tagihan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($invoices->hasPages())
        <div id="laporanPagination" class="px-4 py-3 border-t border-gray-100 dark:border-dark-700">
            {{ $invoices->links() }}
        </div>
    @else
        <div id="laporanPagination" class="hidden"></div>
    @endif

    {{-- Statistik periode --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 border-t border-gray-100 dark:border-dark-700 bg-gray-50/50 dark:bg-dark-700/20">
        <div class="flex items-center gap-3">
            <div class="bg-green-100 dark:bg-green-900/30 p-2.5 rounded-xl">
                <i class='bx bx-trending-up text-green-600 dark:text-green-400 text-xl'></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Total Kas Masuk</p>
                <p class="text-lg font-bold text-green-600 dark:text-green-400">
                    Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-red-100 dark:bg-red-900/30 p-2.5 rounded-xl">
                <i class='bx bx-trending-down text-red-600 dark:text-red-400 text-xl'></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Total Kas Keluar</p>
                <p class="text-lg font-bold text-red-600 dark:text-red-400">
                    Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-purple-100 dark:bg-purple-900/30 p-2.5 rounded-xl">
                <i class='bx bx-calculator text-purple-600 dark:text-purple-400 text-xl'></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Saldo Bulan Ini</p>
                <p class="text-lg font-bold {{ $labaRugi >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    Rp {{ number_format($labaRugi, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>
