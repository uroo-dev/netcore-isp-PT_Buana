<!-- Statistik Cards -->
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                <i class='bx bx-user text-blue-600 dark:text-blue-400'></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Pelanggan</p>
                <p class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($totalPelanggan) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                <i class='bx bx-receipt text-indigo-600 dark:text-indigo-400'></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tagihan</p>
                <p class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($totalTagihan) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                <i class='bx bx-coin-stack text-green-600 dark:text-green-400'></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">DPP</p>
                <p class="text-lg font-bold text-gray-800 dark:text-white">Rp {{ number_format($totalDpp, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                <i class='bx bx-tax text-yellow-600 dark:text-yellow-400'></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">PPN</p>
                <p class="text-lg font-bold text-gray-800 dark:text-white">Rp {{ number_format($totalPpn, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                <i class='bx bx-wifi text-orange-600 dark:text-orange-400'></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">BHP USO</p>
                <p class="text-lg font-bold text-gray-800 dark:text-white">Rp {{ number_format($totalBhp, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                <i class='bx bx-calculator text-amber-600 dark:text-amber-400'></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Proy. Pajak</p>
                <p class="text-lg font-bold text-amber-600 dark:text-amber-400">Rp {{ number_format($totalProyeksi, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Proyeksi Pajak Tahunan -->
<div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
    <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
        <i class='bx bx-line-chart text-gray-600 dark:text-blue-400'></i>
        Proyeksi Pajak {{ $bulan }}
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                <tr>
                    <th class="px-3 py-2 text-left font-semibold">Bulan</th>
                    <th class="px-3 py-2 text-right font-semibold">DPP</th>
                    <th class="px-3 py-2 text-right font-semibold">PPN</th>
                    <th class="px-3 py-2 text-right font-semibold">BHP USO</th>
                    <th class="px-3 py-2 text-right font-semibold text-amber-600">Proyeksi Pajak</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-dark-700">
                @foreach ($grafikBulan as $idx => $namaBulan)
                @php
                    $dppGrafik = $grafikDpp[$idx];
                    $ppnGrafik = $grafikPpn[$idx];
                    $bhpGrafik = $grafikBhp[$idx];
                    $proyeksiGrafik = $grafikProyeksi[$idx];
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                    <td class="px-3 py-2 font-medium text-gray-800 dark:text-white">{{ $namaBulan }}</td>
                    <td class="px-3 py-2 text-right text-gray-600 dark:text-gray-400">
                        Rp {{ number_format($dppGrafik, 0, ',', '.') }}
                    </td>
                    <td class="px-3 py-2 text-right text-gray-600 dark:text-gray-400">
                        Rp {{ number_format($ppnGrafik, 0, ',', '.') }}
                    </td>
                    <td class="px-3 py-2 text-right text-gray-600 dark:text-gray-400">
                        Rp {{ number_format($bhpGrafik, 0, ',', '.') }}
                    </td>
                    <td class="px-3 py-2 text-right font-semibold text-amber-600 dark:text-amber-400">
                        Rp {{ number_format($proyeksiGrafik, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Tabel Invoice -->
<div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class='bx bx-list-ul text-gray-600 dark:text-blue-400'></i>
            Rincian Tagihan
            <span class="text-xs bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded-full">{{ $invoices->total() }}</span>
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No Nota</th>
                    <th class="px-4 py-3 text-left font-semibold">Pelanggan</th>
                    <th class="px-4 py-3 text-center font-semibold hidden sm:table-cell">Tanggal</th>
                    <th class="px-4 py-3 text-right font-semibold">Harga Paket</th>
                    <th class="px-4 py-3 text-right font-semibold hidden lg:table-cell">DPP</th>
                    <th class="px-4 py-3 text-right font-semibold hidden lg:table-cell">PPN</th>
                    <th class="px-4 py-3 text-right font-semibold hidden xl:table-cell">BHP USO</th>
                    <th class="px-4 py-3 text-right font-semibold text-amber-600">Proy. Pajak</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-dark-700">
                @forelse($invoices as $inv)
                @php
                    $dpp = $inv->total / 1.11;
                    $ppn = $inv->total - $dpp;
                    $bhp = $dpp * 0.0175;
                    $proyeksi = $bhp + $ppn;
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                    <td class="px-4 py-3">
                        <a href="{{ route('invoice.show', $inv->id) }}"
                            class="font-mono text-xs font-semibold text-gray-600 dark:text-blue-400 hover:underline">
                            {{ $inv->kode_invoice }}
                        </a>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">{{ $inv->pelanggan->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-center hidden sm:table-cell text-gray-600 dark:text-gray-400">
                        {{ $inv->tanggal ? \Carbon\Carbon::parse($inv->tanggal)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-800 dark:text-white">
                        Rp {{ number_format($inv->total, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-right hidden lg:table-cell text-gray-600 dark:text-gray-400">
                        Rp {{ number_format($dpp, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-right hidden lg:table-cell text-gray-600 dark:text-gray-400">
                        Rp {{ number_format($ppn, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-right hidden xl:table-cell text-gray-600 dark:text-gray-400">
                        Rp {{ number_format($bhp, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-amber-600 dark:text-amber-400">
                        Rp {{ number_format($proyeksi, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class='bx bx-receipt text-4xl mb-2 block'></i>
                        <p>Belum ada tagihan bulan ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="pajakPagination">
        @if ($invoices->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-dark-700">
            {{ $invoices->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>