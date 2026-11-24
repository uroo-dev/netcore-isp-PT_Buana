@extends('layouts.app')
@section('title', 'Kas Perusahaan')
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-category text-gray-600 dark:text-blue-400 text-2xl'></i>
                    Kas Perusahaan
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pengelolaan biaya operasional dan rekonsiliasi
                    keuangan kantor</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="toggleMoney()"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-600 dark:hover:text-blue-400"
                    id="moneyToggleBtn">
                    <i class='bx bx-show text-lg' id="moneyToggleIcon"></i>
                    <span id="moneyToggleLabel">Sembunyikan Jumlah</span>
                </button>
                <button data-modal-target="modalTambah" data-modal-toggle="modalTambah"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-plus text-lg'></i> Tambah Biaya
                </button>
            </div>
        </div>
        <!-- Rekapitulasi Keuangan Bulan Ini -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class='bx bxs-wallet-alt text-gray-600 dark:text-blue-400 text-xl'></i>
                Rekapitulasi Keuangan Bulan {{ \Carbon\Carbon::now()->format('F Y') }}
            </h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-3 text-center lg:text-left">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pemasukan</p>
                    <p class="text-lg font-bold text-green-600 dark:text-green-400">
                        <span class="money-value"
                            data-amount="Rp {{ number_format($keuanganMasukBulanIni, 0, ',', '.') }}">Rp
                            {{ number_format($keuanganMasukBulanIni, 0, ',', '.') }}</span>
                    </p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-3 text-center lg:text-left">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pengeluaran</p>
                    <p class="text-lg font-bold text-red-600 dark:text-red-400">
                        <span class="money-value"
                            data-amount="Rp {{ number_format($keuanganKeluarBulanIni, 0, ',', '.') }}">Rp
                            {{ number_format($keuanganKeluarBulanIni, 0, ',', '.') }}</span>
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/20 rounded-xl p-3 text-center lg:text-left">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Saldo Keuangan</p>
                    <p class="text-lg font-bold text-gray-600 dark:text-blue-400">
                        <span class="money-value" data-amount="Rp {{ number_format($saldoKeuangan, 0, ',', '.') }}">Rp
                            {{ number_format($saldoKeuangan, 0, ',', '.') }}</span>
                    </p>
                </div>
                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-3 text-center lg:text-left">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Biaya Ops. Bulan Ini</p>
                    <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                        <span class="money-value" data-amount="Rp {{ number_format($totalBulanIni, 0, ',', '.') }}">Rp
                            {{ number_format($totalBulanIni, 0, ',', '.') }}</span>
                    </p>
                </div>
            </div>

            @if ($keuanganBulanan->count() > 0)
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-3 py-2 text-left rounded-l-lg">Bulan</th>
                                <th class="px-3 py-2 text-right">Pemasukan</th>
                                <th class="px-3 py-2 text-right">Pengeluaran</th>
                                <th class="px-3 py-2 text-right rounded-r-lg">Selisih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-dark-700">
                            @foreach ($keuanganBulanan as $kb)
                                <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                                    <td class="px-3 py-2 font-medium text-gray-700 dark:text-gray-300">
                                        {{ \Carbon\Carbon::create()->month($kb->bulan)->format('M') }} {{ $kb->tahun }}
                                    </td>
                                    <td class="px-3 py-2 text-right text-green-600 dark:text-green-400">Rp
                                        {{ number_format($kb->total_masuk, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-right text-red-600 dark:text-red-400">Rp
                                        {{ number_format($kb->total_keluar, 0, ',', '.') }}</td>
                                    <td
                                        class="px-3 py-2 text-right font-medium {{ $kb->total_masuk - $kb->total_keluar >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        Rp {{ number_format($kb->total_masuk - $kb->total_keluar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Filter -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <form method="GET" action="{{ route('biaya-operasional.index') }}" id="biayaFilterForm" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[130px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-category text-xs mr-1'></i> Kategori
                    </label>
                    <select name="kategori"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>
                                {{ ucfirst($k) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Tanggal Awal
                    </label>
                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Tanggal Akhir
                    </label>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('biaya-operasional.index') }}" id="biayaResetBtn"
                        class="flex items-center gap-1.5 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </form>

            {{-- Info hasil filter --}}
            @if (request('kategori') || request('tanggal_awal') || request('tanggal_akhir'))
                <div
                    class="mt-4 pt-3 border-t border-gray-100 dark:border-dark-700 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class='bx bx-info-circle'></i>
                    <span>Filter aktif:</span>
                    @if (request('kategori'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Kategori:
                            {{ ucfirst(request('kategori')) }}</span>
                    @endif
                    @if (request('tanggal_awal'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Dari:
                            {{ \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') }}</span>
                    @endif
                    @if (request('tanggal_akhir'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Sampai:
                            {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}</span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Tabel Biaya Operasional -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-16">#</th>
                            <th class="px-4 py-3 text-left font-semibold hidden sm:table-cell">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                            <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                            <th class="px-4 py-3 text-right font-semibold">Jumlah</th>
                            <th class="px-4 py-3 text-left font-semibold hidden lg:table-cell">User</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="biayaTbody" class="divide-y dark:divide-dark-700">
                        @include('pages.biaya-operasional.partials.rows')
                    </tbody>
                </table>
            </div>

            <div id="biayaPagination" class="border-t border-gray-100 dark:border-dark-700">
                @include('pages.biaya-operasional.partials.pagination')
            </div>
        </div>
    </div>
    <div class="p-4 md:p-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-xl">
                        <i class='bx bx-calendar text-red-600 dark:text-red-400 text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Biaya Bulan Ini</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                            <span class="money-value"
                                data-amount="Rp {{ number_format($totalBulanIni, 0, ',', '.') }}">Rp
                                {{ number_format($totalBulanIni, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-xl">
                        <i class='bx bx-history text-orange-600 dark:text-orange-400 text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Total Seluruh Biaya</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                            <span class="money-value" data-amount="Rp {{ number_format($totalSemua, 0, ',', '.') }}">Rp
                                {{ number_format($totalSemua, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Biaya Operasional -->
    <div id="modalTambah" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-plus-circle text-gray-600 text-xl'></i>
                    Tambah Biaya Operasional
                </h3>
                <button data-modal-hide="modalTambah"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('biaya-operasional.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Kategori</label>
                        <select name="kategori" required
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            <option value="">Pilih</option>
                            <option value="listrik">⚡ Listrik</option>
                            <option value="sewa">🏢 Sewa</option>
                            <option value="gaji">💰 Gaji</option>
                            <option value="transport">🚗 Transport</option>
                            <option value="konsumsi">🍽️ Konsumsi</option>
                            <option value="umum">📋 Umum</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" required placeholder="Misal: Bayar listrik bulan ini"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Jumlah (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" id="jumlahInput"
                            class="w-full pl-8 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-right text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="0">
                        <input type="hidden" name="jumlah" id="jumlahRaw">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" data-modal-hide="modalTambah"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-700 hover:bg-gray-50 dark:hover:bg-dark-700 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-xl transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        // Format Rupiah untuk input jumlah
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function parseRupiah(str) {
            return parseInt(str.replace(/\./g, '')) || 0;
        }

        // Setup untuk modal tambah
        const jumlahInput = document.getElementById('jumlahInput');
        const jumlahRaw = document.getElementById('jumlahRaw');

        if (jumlahInput && jumlahRaw) {
            jumlahInput.addEventListener('input', function() {
                let value = parseRupiah(this.value);
                this.value = formatRupiah(value);
                jumlahRaw.value = value;
            });
        }

        // Setup untuk modal edit (delegasi event karena tbody di-render ulang via AJAX)
        document.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('edit-jumlah-input')) {
                let value = parseRupiah(e.target.value);
                e.target.value = formatRupiah(value);
                const wrapper = e.target.closest('.relative');
                if (wrapper) {
                    const raw = wrapper.querySelector('.edit-jumlah-raw');
                    if (raw) raw.value = value;
                }
            }
        });

        // SweetAlert untuk konfirmasi hapus
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Yakin hapus biaya operasional?',
                text: "Data kas terkait juga akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // AJAX Filter & Search
        const biayaForm = document.getElementById('biayaFilterForm');
        const biayaTbody = document.getElementById('biayaTbody');
        const biayaPagination = document.getElementById('biayaPagination');
        const biayaResetBtn = document.getElementById('biayaResetBtn');

        async function fetchBiaya(page = null) {
            const params = new URLSearchParams(new FormData(biayaForm));
            if (page) params.set('page', page);

            try {
                const res = await fetch('{{ route('biaya-operasional.data') }}' + '?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                biayaTbody.innerHTML = data.rows;
                biayaPagination.innerHTML = data.pagination;
                bindBiayaPagination();
            } catch (e) {
                window.location.href = '{{ route('biaya-operasional.index') }}' + '?' + params.toString();
            }
        }

        function bindBiayaPagination() {
            biayaPagination.querySelectorAll('a[href]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    fetchBiaya(url.searchParams.get('page'));
                });
            });
        }

        biayaForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchBiaya(1);
        });

        biayaResetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            biayaForm.reset();
            fetchBiaya(1);
        });

        bindBiayaPagination();
    </script>
@endpush
