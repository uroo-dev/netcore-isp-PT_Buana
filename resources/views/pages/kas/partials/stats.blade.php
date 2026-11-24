{{-- Statistik periode --}}
<div class="overflow-x-auto border-t border-gray-100 dark:border-dark-700 bg-gray-50/50 dark:bg-dark-700/20">
    <div class="grid grid-cols-4 gap-4 p-4 min-w-[900px]">

        <div class="flex items-center gap-3">
            <div class="bg-green-100 dark:bg-green-900/30 p-2.5 rounded-xl">
                <i class='bx bx-trending-up text-green-600 dark:text-green-400 text-xl'></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">
                    Total Uang Masuk
                </p>
                <p class="text-lg font-bold text-green-600 dark:text-green-400">
                    Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-red-100 dark:bg-red-900/30 p-2.5 rounded-xl">
                <i class='bx bx-trending-down text-red-600 dark:text-red-400 text-xl'></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">
                    Total Uang Keluar
                </p>
                <p class="text-lg font-bold text-red-600 dark:text-red-400">
                    Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-blue-100 dark:bg-blue-900/30 p-2.5 rounded-xl">
                <i class='bx bx-skip-previous text-blue-600 dark:text-blue-400 text-xl'></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">
                    Saldo Awal Periode
                </p>
                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                    Rp {{ number_format($saldoSebelum, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-orange-100 dark:bg-orange-900/30 p-2.5 rounded-xl">
                <i class='bx bx-calculator text-orange-600 dark:text-orange-400 text-xl'></i>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">
                    Saldo Hari ini
                </p>
                <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                    Rp {{ number_format($saldoPeriode, 0, ',', '.') }}
                </p>
            </div>
        </div>

    </div>
</div>

{{-- Saldo per hari --}}
@if ($hasDateFilter && $saldoPerHari->isNotEmpty())
    <div class="border-t border-gray-100 dark:border-dark-700">
        <div class="p-4">
            <div class="flex items-center gap-2 mb-3">
                <i class='bx bx-line-chart text-gray-500 dark:text-gray-400 text-lg'></i>
                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                    Saldo Per Hari</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-xs border-b dark:border-dark-700">
                            <th class="px-3 py-2 text-left font-medium">Tanggal</th>
                            <th class="px-3 py-2 text-right font-medium">Uang Masuk</th>
                            <th class="px-3 py-2 text-right font-medium">Uang Keluar</th>
                            <th class="px-3 py-2 text-right font-medium">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-dark-700">
                        @foreach ($saldoPerHari as $hari)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-dark-700/20 transition">
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($hari->tanggal)->format('d M Y') }}
                                </td>
                                <td
                                    class="px-3 py-2 text-right font-medium text-green-600 dark:text-green-400">
                                    Rp {{ number_format($hari->total_masuk, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2 text-right font-medium text-red-600 dark:text-red-400">
                                    Rp {{ number_format($hari->total_keluar, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-3 py-2 text-right font-semibold text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($hari->saldo_akhir, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
