@extends('layouts.app')
@section('title', 'Keuangan')
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bxs-wallet text-gray-600 dark:text-blue-400 text-2xl'></i>
                    Keuangan
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Catatan keuangan: pemasukkan, pengeluaran,
                    pembelian, dan pembayaran</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="toggleMoney()"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-600 dark:hover:text-blue-400"
                    id="moneyToggleBtn">
                    <i class='bx bx-show text-lg' id="moneyToggleIcon"></i>
                    <span id="moneyToggleLabel">Sembunyikan Jumlah</span>
                </button>
                <button onclick="openModal('cetakLaporanKasModal')"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">
                    <i class='bx bx-printer text-lg'></i> Cetak Laporan
                </button>
                <button data-modal-target="modalMasuk" data-modal-toggle="modalMasuk"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-plus-circle text-lg'></i> Pemasukkan
                </button>
                <button data-modal-target="modalKeluar" data-modal-toggle="modalKeluar"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-minus-circle text-lg'></i> Pengeluaran
                </button>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-600/10 p-3 rounded-xl">
                        <i class='bx bx-wallet text-gray-600 dark:text-blue-400 text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Total Saldo</p>
                        <p class="text-2xl font-bold text-gray-600 dark:text-blue-400">
                            <span class="money-value" data-amount="Rp {{ number_format($saldoSekarang, 0, ',', '.') }}">Rp
                                {{ number_format($saldoSekarang, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-xl">
                        <i class='bx bx-trending-up text-green-600 dark:text-green-400 text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Uang Masuk Bulan Ini</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            <span class="money-value"
                                data-amount="Rp {{ number_format($totalMasukBulanIni, 0, ',', '.') }}">Rp
                                {{ number_format($totalMasukBulanIni, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-xl">
                        <i class='bx bx-trending-down text-red-600 dark:text-red-400 text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Uang Keluar Bulan Ini
                        </p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                            <span class="money-value"
                                data-amount="Rp {{ number_format($totalKeluarBulanIni, 0, ',', '.') }}">Rp
                                {{ number_format($totalKeluarBulanIni, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-xl">
                        <i class='bx bx-calendar-event text-purple-600 dark:text-purple-400 text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Saldo Bulan Ini</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            <span class="money-value" data-amount="Rp {{ number_format($kasBulanIni, 0, ',', '.') }}">Rp
                                {{ number_format($kasBulanIni, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <form method="GET" action="{{ route('kas.index') }}" id="kasFilterForm" class="flex flex-wrap items-end gap-4">

                <div class="flex-1 min-w-[130px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-transfer text-xs mr-1'></i> Tipe
                    </label>
                    <select name="tipe"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">Semua Tipe</option>
                        <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>💰 Uang Masuk</option>
                        <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>💸 Uang Keluar</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Tanggal Awal
                    </label>
                    <input type="date" name="tanggal_awal"
                        value="{{ request('tanggal_awal') }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Tanggal Akhir
                    </label>
                    <input type="date" name="tanggal_akhir"
                        value="{{ request('tanggal_akhir') }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('kas.index') }}" id="kasResetBtn"
                        class="flex items-center gap-1.5 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </form>

            <form method="POST" action="{{ route('kas.rekalkulasi') }}" onsubmit="return confirm('Rekalkulasi semua saldo kas? Pastikan data sudah benar.')"
                class="mt-4">
                @csrf
                <button type="submit"
                    class="flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 border border-indigo-200 dark:border-indigo-800">
                    <i class='bx bx-revision'></i> Rekalkulasi Saldo
                </button>
            </form>

            {{-- Info filter aktif --}}
            <div id="kasFilterInfo"
                class="mt-4 pt-3 border-t border-gray-100 dark:border-dark-700 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <i class='bx bx-info-circle'></i>
                <span>Filter aktif:</span>
                @if (request('tipe'))
                    <span
                        class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                        Tipe: {{ request('tipe') == 'masuk' ? 'Uang Masuk' : 'Uang Keluar' }}
                    </span>
                @endif
                @if (request('tanggal_awal'))
                    <span
                        class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                        Dari: {{ \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') }}
                    </span>
                @endif
                @if (request('tanggal_akhir'))
                    <span
                        class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                        Sampai: {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Tabel Keuangan -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-16">#</th>
                            <th class="px-4 py-3 text-left font-semibold hidden sm:table-cell">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold">Tipe</th>
                            <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                            <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">User</th>
                            <th class="px-4 py-3 text-right font-semibold">Jumlah</th>
                            <th class="px-4 py-3 text-right font-semibold hidden lg:table-cell">Saldo</th>
                        </tr>
                    </thead>
                    <tbody id="kasTbody" class="divide-y dark:divide-dark-700">
                        @include('pages.kas.partials.table-rows')
                    </tbody>
                </table>
            </div>

            @if ($kas->hasPages())
                <div id="kasPagination" class="p-4 border-t border-gray-100 dark:border-dark-700">
                    {{ $kas->links() }}
                </div>
            @else
                <div id="kasPagination" class="hidden"></div>
            @endif

            {{-- Statistik periode & saldo per hari (diupdate AJAX) --}}
            <div id="kasStats">
                @include('pages.kas.partials.stats')
            </div>
        </div>
    </div>

    <!-- Modal Pemasukkan -->
    <div id="modalMasuk" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 flex items-center gap-2">
                    <i class='bx bx-plus-circle text-xl'></i>
                    Tambah Pemasukkan
                </h3>
                <button data-modal-hide="modalMasuk"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('kas.store') }}" method="POST" class="space-y-4" id="formMasuk">
                @csrf
                <input type="hidden" name="tipe" value="masuk">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" required placeholder="Contoh: Pembayaran invoice INV-001"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Jumlah (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" id="jumlahMasuk"
                            class="w-full pl-8 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-right text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="0">
                        <input type="hidden" name="jumlah" id="jumlahMasukRaw">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" data-modal-hide="modalMasuk"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-700 hover:bg-gray-50 dark:hover:bg-dark-700 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-xl transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pengeluaran -->
    <div id="modalKeluar" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 flex items-center gap-2">
                    <i class='bx bx-minus-circle text-xl'></i>
                    Tambah Pengeluaran
                </h3>
                <button data-modal-hide="modalKeluar"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('kas.store') }}" method="POST" class="space-y-4" id="formKeluar">
                @csrf
                <input type="hidden" name="tipe" value="keluar">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" required placeholder="Contoh: Beli ATK, bayar listrik"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Jumlah (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" id="jumlahKeluar"
                            class="w-full pl-8 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-right text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="0">
                        <input type="hidden" name="jumlah" id="jumlahKeluarRaw">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" data-modal-hide="modalKeluar"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-700 hover:bg-gray-50 dark:hover:bg-dark-700 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

{{-- Modal Cetak Laporan Kas --}}
<div id="cetakLaporanKasModal"
    class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm">
    <div
        class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-700 w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 dark:text-white text-lg">
                <i class='bx bx-printer text-purple-600 mr-2'></i>Cetak Laporan Kas Perusahaan
            </h3>
            <button type="button" onclick="closeModal('cetakLaporanKasModal')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form action="{{ route('laporan.cetak-kas-perusahaan') }}" method="GET" target="_blank"
            class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Tanggal Akhir
                    </label>
                    <input type="date" name="tanggal_akhir" value="{{ now()->format('Y-m-d') }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeModal('cetakLaporanKasModal')"
                    class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-xl transition-colors flex items-center gap-1">
                    <i class='bx bx-printer'></i> Cetak PDF
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    document.addEventListener('click', function(e) {
        document.querySelectorAll('[id$="KasModal"]').forEach(function(modal) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>

@push('scripts')
    <script>
        // Format Rupiah untuk input jumlah
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function parseRupiah(str) {
            return parseInt(str.replace(/\./g, '')) || 0;
        }

        // Setup untuk modal pemasukkan
        const jumlahMasuk = document.getElementById('jumlahMasuk');
        const jumlahMasukRaw = document.getElementById('jumlahMasukRaw');
        if (jumlahMasuk && jumlahMasukRaw) {
            jumlahMasuk.addEventListener('input', function() {
                let value = parseRupiah(this.value);
                this.value = formatRupiah(value);
                jumlahMasukRaw.value = value;
            });
        }

        // Setup untuk modal pengeluaran
        const jumlahKeluar = document.getElementById('jumlahKeluar');
        const jumlahKeluarRaw = document.getElementById('jumlahKeluarRaw');
        if (jumlahKeluar && jumlahKeluarRaw) {
            jumlahKeluar.addEventListener('input', function() {
                let value = parseRupiah(this.value);
                this.value = formatRupiah(value);
                jumlahKeluarRaw.value = value;
            });
        }

        // Tutup modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = ['modalMasuk', 'modalKeluar'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains('hidden')) {
                        const closeBtn = modal.querySelector('[data-modal-hide="' + modalId + '"]');
                        if (closeBtn) closeBtn.click();
                    }
                });
            }
        });
    </script>

    {{-- ========== AJAX FILTER LAPORAN KEUANGAN ========== --}}
    <script>
        (function() {
            const form = document.getElementById('kasFilterForm');
            const tbody = document.getElementById('kasTbody');
            const paginationEl = document.getElementById('kasPagination');
            const statsEl = document.getElementById('kasStats');
            const infoEl = document.getElementById('kasFilterInfo');
            const resetBtn = document.getElementById('kasResetBtn');
            const DATA_URL = '{{ route('kas.data') }}';

            function getParams(page) {
                const p = new URLSearchParams();
                p.set('tipe', form.tipe.value || '');
                p.set('tanggal_awal', form.tanggal_awal.value || '');
                p.set('tanggal_akhir', form.tanggal_akhir.value || '');
                p.set('page', page || '1');
                return p;
            }

            function fmtTanggal(d) {
                const parts = String(d).split('-');
                return parts.length === 3 ? parts[2] + '/' + parts[1] + '/' + parts[0] : d;
            }

            function updateInfo() {
                if (!infoEl) return;
                const tipe = form.tipe.value;
                const awal = form.tanggal_awal.value;
                const akhir = form.tanggal_akhir.value;

                let chips = '<i class="bx bx-info-circle"></i><span>Filter aktif:</span>';
                if (tipe) {
                    chips += '<span class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Tipe: ' +
                        (tipe === 'masuk' ? 'Uang Masuk' : 'Uang Keluar') + '</span>';
                }
                if (awal) {
                    chips += '<span class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Dari: ' +
                        fmtTanggal(awal) + '</span>';
                }
                if (akhir) {
                    chips += '<span class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Sampai: ' +
                        fmtTanggal(akhir) + '</span>';
                }
                const hasFilter = !!(tipe || awal || akhir);
                infoEl.classList.toggle('hidden', !hasFilter);
                if (hasFilter) infoEl.innerHTML = chips;
            }

            function applyMoneyState() {
                if (typeof moneyVisible !== 'undefined' && !moneyVisible) {
                    document.querySelectorAll('.money-value').forEach(function(el) {
                        el.textContent = 'Rp ****';
                    });
                }
            }

            function fetchKas(page) {
                const url = DATA_URL + '?' + getParams(page);
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">' +
                        '<span class="inline-block animate-spin rounded-full border-2 border-gray-300 border-t-gray-700 w-5 h-5 mr-2 align-middle"></span>Memuat data...</td></tr>';
                }
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (tbody) tbody.innerHTML = data.rows;
                        if (statsEl) statsEl.innerHTML = data.stats;
                        if (paginationEl) {
                            if (data.pagination) {
                                paginationEl.classList.remove('hidden');
                                paginationEl.innerHTML = data.pagination;
                            } else {
                                paginationEl.innerHTML = '';
                                paginationEl.classList.add('hidden');
                            }
                            bindPagination();
                        }
                        applyMoneyState();
                    })
                    .catch(function() {
                        if (tbody) {
                            tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-red-400">Gagal memuat data</td></tr>';
                        }
                    });
            }

            function bindPagination() {
                if (!paginationEl) return;
                paginationEl.querySelectorAll('a[href]').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        fetchKas(url.searchParams.get('page') || 1);
                    });
                });
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    fetchKas(1);
                    updateInfo();
                });
            }
            if (resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    form.reset();
                    fetchKas(1);
                    updateInfo();
                });
            }
            bindPagination();
        })();
    </script>
@endpush
