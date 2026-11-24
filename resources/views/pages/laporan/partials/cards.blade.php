<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
    <div
        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
        <div class="flex items-center gap-2 mb-2">
            <div class="bg-gray-600/10 p-1.5 rounded-lg">
                <i class='bx bx-wallet text-gray-600 dark:text-blue-400 text-sm'></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Total Saldo</p>
        </div>
        <p class="text-lg font-bold text-gray-600 dark:text-blue-400">
            <span class="money-value" data-amount="Rp {{ number_format($saldoKas, 0, ',', '.') }}">Rp
                {{ number_format($saldoKas, 0, ',', '.') }}</span>
        </p>
    </div>
    <div
        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
        <div class="flex items-center gap-2 mb-2">
            <div class="bg-green-100 dark:bg-green-900/30 p-1.5 rounded-lg">
                <i class='bx bx-trending-up text-green-600 dark:text-green-400 text-sm'></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Uang Masuk Bulan Ini</p>
        </div>
        <p class="text-lg font-bold text-green-600 dark:text-green-400">
            <span class="money-value" data-amount="Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}">Rp
                {{ number_format($totalKasMasuk, 0, ',', '.') }}</span>
        </p>
    </div>
    <div
        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
        <div class="flex items-center gap-2 mb-2">
            <div class="bg-red-100 dark:bg-red-900/30 p-1.5 rounded-lg">
                <i class='bx bx-trending-down text-red-600 dark:text-red-400 text-sm'></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Uang Keluar Bulan Ini</p>
        </div>
        <p class="text-lg font-bold text-red-600 dark:text-red-400">
            <span class="money-value" data-amount="Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}">Rp
                {{ number_format($totalKasKeluar, 0, ',', '.') }}</span>
        </p>
    </div>
    <div
        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
        <div class="flex items-center gap-2 mb-2">
            <div class="bg-purple-100 dark:bg-purple-900/30 p-1.5 rounded-lg">
                <i class='bx bx-calendar-event text-purple-600 dark:text-purple-400 text-sm'></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Saldo Bulan Ini</p>
        </div>
        <p
            class="text-lg font-bold {{ $labaRugi >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
            <span class="money-value" data-amount="Rp {{ number_format($labaRugi, 0, ',', '.') }}">Rp
                {{ number_format($labaRugi, 0, ',', '.') }}</span>
        </p>
    </div>
    <div
        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
        <div class="flex items-center gap-2 mb-2">
            <div class="bg-gray-100 dark:bg-gray-900/30 p-1.5 rounded-lg">
                <i class='bx bx-shopping-bag text-gray-600 dark:text-blue-400 text-sm'></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Total Penjualan</p>
        </div>
        <p class="text-lg font-bold text-gray-600 dark:text-blue-400">
            <span class="money-value" data-amount="Rp {{ number_format($totalPenjualan, 0, ',', '.') }}">Rp
                {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
        </p>
    </div>
    <div
        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
        <div class="flex items-center gap-2 mb-2">
            <div class="bg-orange-100 dark:bg-orange-900/30 p-1.5 rounded-lg">
                <i class='bx bx-receipt text-orange-600 dark:text-orange-400 text-sm'></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Total Piutang</p>
        </div>
        <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
            <span class="money-value" data-amount="Rp {{ number_format($totalPiutang, 0, ',', '.') }}">Rp
                {{ number_format($totalPiutang, 0, ',', '.') }}</span>
        </p>
    </div>
</div>
