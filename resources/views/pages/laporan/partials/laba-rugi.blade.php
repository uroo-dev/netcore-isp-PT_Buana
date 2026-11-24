<div
    class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class='bx bx-calculator text-gray-600 text-xl'></i>
            Perhitungan Laba Rugi
        </h3>
    </div>
    <div class="p-5 space-y-3">
        <div class="flex justify-between items-center py-2">
            <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                <i class='bx bx-trending-up text-green-500'></i> Total Kas Masuk
            </span>
            <span class="text-green-600 dark:text-green-400 font-semibold">
                <span class="money-value"
                    data-amount="Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}">Rp
                    {{ number_format($totalKasMasuk, 0, ',', '.') }}</span>
            </span>
        </div>
        <div class="flex justify-between items-center py-2 border-t border-gray-100 dark:border-dark-700">
            <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                <i class='bx bx-trending-down text-red-500'></i> Total Kas Keluar
            </span>
            <span class="text-red-600 dark:text-red-400 font-semibold">
                <span class="money-value"
                    data-amount="Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}">Rp
                    {{ number_format($totalKasKeluar, 0, ',', '.') }}</span>
            </span>
        </div>
        <div class="border-t border-gray-200 dark:border-dark-700 pt-3 mt-2">
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-800 dark:text-white text-base">Laba / Rugi</span>
                <span
                    class="font-bold text-xl {{ $labaRugi >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    <span class="money-value" data-amount="Rp {{ number_format($labaRugi, 0, ',', '.') }}">Rp
                        {{ number_format($labaRugi, 0, ',', '.') }}</span>
                </span>
            </div>
        </div>

        <div class="mt-5 pt-4 border-t border-dashed border-gray-200 dark:border-dark-700">
            <h4 class="font-semibold text-sm text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                <i class='bx bx-detail text-gray-500'></i> Rincian
            </h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1">
                    <span class="text-gray-600 dark:text-gray-400">Penjualan (Invoice)</span>
                    <span class="font-medium text-gray-800 dark:text-white">
                        <span class="money-value"
                            data-amount="Rp {{ number_format($totalPenjualan, 0, ',', '.') }}">Rp
                            {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                    </span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-600 dark:text-gray-400">Piutang (Belum Lunas)</span>
                    <span class="font-medium text-orange-600 dark:text-orange-400">
                        <span class="money-value"
                            data-amount="Rp {{ number_format($totalPiutang, 0, ',', '.') }}">Rp
                            {{ number_format($totalPiutang, 0, ',', '.') }}</span>
                    </span>
                </div>
            </div>
        </div>

        @php
            $profitMargin = $totalPenjualan > 0 ? ($labaRugi / $totalPenjualan) * 100 : 0;
        @endphp
        <div class="mt-4 pt-2">
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                <span>Margin Keuntungan</span>
                <span
                    class="{{ $profitMargin >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ round($profitMargin, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-dark-700 rounded-full h-1.5">
                <div class="bg-green-500 h-1.5 rounded-full transition-all duration-500"
                    style="width: {{ max(0, min(100, $profitMargin)) }}%"></div>
            </div>
        </div>
    </div>
</div>
