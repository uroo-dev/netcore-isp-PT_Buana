@forelse($kas as $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
            {{ $kas->firstItem() + $loop->index }}</td>
        <td class="px-4 py-3 hidden sm:table-cell text-gray-600 dark:text-gray-400">
            {{ $item->tanggal->format('d M Y') }}</td>
        <td class="px-4 py-3">
            @if ($item->tipe === 'masuk')
                <span
                    class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-lg text-xs font-medium">
                    <i class='bx bx-trending-up text-xs'></i> Masuk
                </span>
            @else
                <span
                    class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2.5 py-1 rounded-lg text-xs font-medium">
                    <i class='bx bx-trending-down text-xs'></i> Keluar
                </span>
            @endif
            <div class="sm:hidden text-xs text-gray-400 dark:text-gray-500 mt-1">
                {{ $item->tanggal->format('d M Y') }}</div>
        </td>
        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">
            {{ $item->keterangan }}</td>
        <td class="px-4 py-3 hidden md:table-cell text-gray-500 dark:text-gray-400 text-xs">
            {{ $item->user?->nama_lengkap ?? '-' }}</td>
        <td
            class="px-4 py-3 text-right font-semibold {{ $item->tipe === 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
            <span class="money-value"
                data-amount="{{ $item->tipe === 'masuk' ? '+' : '-' }} Rp {{ number_format($item->jumlah, 0, ',', '.') }}">
                {{ $item->tipe === 'masuk' ? '+' : '-' }} Rp
                {{ number_format($item->jumlah, 0, ',', '.') }}
            </span>
        </td>
        <td
            class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-blue-400 hidden lg:table-cell">
            <span class="money-value"
                data-amount="Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}">
                Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}
            </span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
            <i class='bx bxs-wallet text-4xl mb-2 block'></i>
            <p>Belum ada data keuangan</p>
        </td>
    </tr>
@endforelse
