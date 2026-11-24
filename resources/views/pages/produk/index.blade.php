@extends('layouts.app')
@section('title', 'Stok Produk')
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bxs-package text-gray-600 dark:text-blue-400 text-2xl'></i>
                    Manajemen Produk
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola stok produk dengan mudah</p>
            </div>
            <div class="flex items-center gap-2">
                <button data-modal-target="modalPindahCabang" data-modal-toggle="modalPindahCabang"
                    class="border-2 border-purple-600 text-purple-600 dark:text-purple-400 dark:border-purple-500 hover:bg-purple-600 hover:text-white dark:hover:bg-purple-600 px-4 py-2.5 rounded-xl text-sm flex items-center gap-2 transition-all duration-200">
                    <i class='bx bx-transfer-alt text-lg'></i>
                    <span class="hidden sm:inline">Pindahkan Data</span>
                </button>
                <button onclick="openModal('cetakLaporanProdukModal')"
                    class="border-2 border-green-600 text-green-600 dark:text-green-400 dark:border-green-500 hover:bg-green-600 hover:text-white dark:hover:bg-green-600 px-4 py-2.5 rounded-xl text-sm flex items-center gap-2 transition-all duration-200">
                    <i class='bx bx-printer text-lg'></i>
                    <span class="hidden sm:inline">Cetak Laporan</span>
                </button>
                <button data-modal-target="modalProduk" data-modal-toggle="modalProduk"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm flex items-center gap-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-plus text-lg'></i>
                    <span>Tambah Produk</span>
                </button>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-100 dark:bg-gray-900/30 p-3 rounded-xl">
                        <i class='bx bxs-package text-gray-600 dark:text-blue-400 text-xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Total Produk</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalProduk }}</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-xl">
                        <i class='bx bxs-check-circle text-green-600 dark:text-green-400 text-xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Tersedia</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $totalTersedia }}</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-xl">
                        <i class='bx bxs-error text-yellow-600 dark:text-yellow-400 text-xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Menipis (≤10)</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalMenipis }}</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-xl">
                        <i class='bx bxs-x-circle text-red-600 dark:text-red-400 text-xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Habis</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $totalHabis }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Pencarian -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <form method="GET" action="{{ route('produk.index') }}" id="produkFilterForm" class="flex flex-wrap items-end gap-4">
                <!-- Kategori -->
                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-category text-xs mr-1'></i> Kategori
                    </label>
                    <select name="kategori" id="kategoriFilter"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Stok -->
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-filter-alt text-xs mr-1'></i> Status Stok
                    </label>
                    <select name="stok_status"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">Semua</option>
                        <option value="tersedia" {{ request('stok_status') == 'tersedia' ? 'selected' : '' }}>Tersedia (>10)
                        </option>
                        <option value="menipis" {{ request('stok_status') == 'menipis' ? 'selected' : '' }}>Menipis (≤10)
                        </option>
                        <option value="habis" {{ request('stok_status') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                <!-- Pencarian -->
                <div class="flex-[2] min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-search text-xs mr-1'></i> Cari Produk
                    </label>
                    <div class="relative">
                        <i
                            class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama produk..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('produk.index') }}" id="produkResetBtn"
                        class="flex items-center gap-1.5 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </form>

            {{-- Info hasil filter --}}
            @if (request('kategori') || request('stok_status') || request('q'))
                <div
                    class="mt-4 pt-3 border-t border-gray-100 dark:border-dark-700 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class='bx bx-info-circle'></i>
                    <span>Filter aktif:</span>
                    @if (request('kategori'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Kategori:
                            {{ request('kategori') }}</span>
                    @endif
                    @if (request('stok_status'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Stok:
                            {{ request('stok_status') }}</span>
                    @endif
                    @if (request('q'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">Cari:
                            "{{ request('q') }}"</span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Tabel Produk -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-16">#</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Produk</th>
                            <th class="px-4 py-3 text-left font-semibold hidden sm:table-cell">Kategori</th>
                            <th class="px-4 py-3 text-right font-semibold hidden md:table-cell">Harga Beli</th>
                            <th class="px-4 py-3 text-right font-semibold">Harga Jual</th>
                            <th class="px-4 py-3 text-center font-semibold">Stok</th>
                            <th class="px-4 py-3 text-center font-semibold hidden lg:table-cell">Status</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="produkTbody" class="divide-y dark:divide-dark-700">
                        @include('pages.produk.partials.rows')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="produkPagination" class="border-t border-gray-100 dark:border-dark-700">
                @include('pages.produk.partials.pagination')
            </div>
        </div>
    </div>
@endsection
<!-- Modal Tambah Produk -->
<div id="modalProduk" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class='bx bx-plus-circle text-gray-600 mr-2'></i>
                Tambah Produk
            </h3>
            <button data-modal-hide="modalProduk"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
        </div>
        <form action="{{ route('produk.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nama Produk</label>
                <input type="text" name="nama" required
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Kategori</label>
                <select name="kategori" id="kategoriInput"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white">
                    <option value="">Pilih atau ketik kategori</option>
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Stok Awal</label>
                <input type="number" name="stok" min="0" value="0" required
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Harga Beli</label>
                <input type="number" name="harga_beli" min="0" value="0"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Harga Jual</label>
                <input type="number" name="harga" min="0" required
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-modal-hide="modalProduk"
                    class="px-4 py-2 bg-gray-200 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm hover:bg-gray-300 dark:hover:bg-dark-600 transition">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pindahkan Data ke Cabang -->
<div id="modalPindahCabang" tabindex="-1"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-dark-800 w-full max-w-2xl rounded-2xl shadow-xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class='bx bx-transfer-alt text-purple-600 mr-2'></i>
                Pindahkan Stok ke Cabang
            </h3>
            <button data-modal-hide="modalPindahCabang"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
        </div>
        <form action="{{ route('produk.pindah-cabang') }}" method="POST" class="space-y-4" id="formPindahCabang">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Pilih Cabang</label>
                <select name="cabang_id" required id="cabangSelect"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach ($cabangs as $c)
                        <option value="{{ $c->id }}">{{ $c->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="border border-gray-200 dark:border-dark-700 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400">
                        <tr>
                            <th class="px-3 py-2 text-left w-10">
                                <input type="checkbox" id="checkAllProduk" class="rounded">
                            </th>
                            <th class="px-3 py-2 text-left">Produk</th>
                            <th class="px-3 py-2 text-center w-20">Stok</th>
                            <th class="px-3 py-2 text-center w-28">Jumlah Pindah</th>
                        </tr>
                    </thead>
                    <tbody id="pindahTbody" class="divide-y dark:divide-dark-700">
                        @include('pages.produk.partials.pindah')
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-modal-hide="modalPindahCabang"
                    class="px-4 py-2 bg-gray-200 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
                <button type="submit" id="btnPindah"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm">Pindahkan</button>
            </div>
        </form>
    </div>
</div>

<datalist id="kategoriList">
    @foreach ($kategoris as $kat)
        <option value="{{ $kat }}">
    @endforeach
</datalist>

{{-- Modal Cetak Laporan Produk --}}
<div id="cetakLaporanProdukModal"
    class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm">
    <div
        class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-700 w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 dark:text-white text-lg">
                <i class='bx bx-printer text-green-600 mr-2'></i>Cetak Laporan Mutasi Stok
            </h3>
            <button type="button" onclick="closeModal('cetakLaporanProdukModal')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form action="{{ route('laporan.cetak-produk') }}" method="GET" target="_blank" class="p-6 space-y-4">
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
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                    <i class='bx bx-filter text-xs mr-1'></i> Tipe
                </label>
                <select name="tipe"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    <option value="">Semua</option>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                    <option value="pindah">Pindah</option>
                </select>
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeModal('cetakLaporanProdukModal')"
                    class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors flex items-center gap-1">
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
        document.querySelectorAll('[id$="ProdukModal"]').forEach(function(modal) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>

@push('scripts')
    <script>
        // Check all produk (delegasi event karena tbody di-render ulang via AJAX)
        document.addEventListener('change', function(e) {
            if (e.target && e.target.id === 'checkAllProduk') {
                document.querySelectorAll('#pindahTbody .produk-checkbox').forEach(cb => {
                    cb.checked = e.target.checked;
                    const row = cb.closest('tr');
                    if (row) {
                        const input = row.querySelector('input[type="number"]');
                        if (input) input.disabled = !e.target.checked;
                    }
                });
            }
        });

        // Sync checkbox dengan jumlah input (delegasi event)
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('produk-checkbox')) {
                const row = e.target.closest('tr');
                if (row) {
                    const input = row.querySelector('input[type="number"]');
                    if (input) {
                        input.disabled = !e.target.checked;
                        if (!e.target.checked) input.value = 0;
                    }
                }
            }
        });

        // SweetAlert confirm delete
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Yakin hapus produk?',
                text: "Data produk akan dihapus permanen!",
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

        // Fungsi hitung biaya restok
        function hitungBiaya(id, hargaBeli) {
            const stok = document.getElementById('stok' + id).value;
            const total = document.getElementById('totalBiaya' + id);
            if (stok && hargaBeli > 0) {
                total.value = stok * hargaBeli;
            } else if (stok && hargaBeli == 0) {
                total.value = 0;
            }
        }

        // Inisialisasi Select2 dengan dark mode support
        $(document).ready(function() {
            $('#kategoriFilter').select2({
                placeholder: 'Cari Kategori',
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                theme: document.documentElement.classList.contains('dark-mode') ? 'classic' : 'default'
            });

            $('#kategoriInput').select2({
                placeholder: 'Cari atau ketik kategori',
                allowClear: true,
                tags: true,
                width: '100%',
                dropdownAutoWidth: true,
                dropdownParent: '#modalProduk',
                theme: document.documentElement.classList.contains('dark-mode') ? 'classic' : 'default'
            });

            // Update select2 theme saat dark mode berubah
            window.addEventListener('themeChanged', function(e) {
                var theme = e.detail.theme === 'dark' ? 'classic' : 'default';

                $('#kategoriFilter').select2('destroy').select2({
                    placeholder: 'Cari Kategori',
                    allowClear: true,
                    width: '100%',
                    theme: theme
                });

                $('#kategoriInput').select2('destroy').select2({
                    placeholder: 'Cari atau ketik kategori',
                    allowClear: true,
                    tags: true,
                    width: '100%',
                    dropdownAutoWidth: true,
                    dropdownParent: '#modalProduk',
                    theme: theme
                });
            });
        });

        // AJAX Filter & Search
        const produkForm = document.getElementById('produkFilterForm');
        const produkTbody = document.getElementById('produkTbody');
        const produkPagination = document.getElementById('produkPagination');
        const pindahTbody = document.getElementById('pindahTbody');
        const produkResetBtn = document.getElementById('produkResetBtn');

        async function fetchProduk(page = null) {
            const params = new URLSearchParams(new FormData(produkForm));
            if (page) params.set('page', page);

            try {
                const res = await fetch('{{ route('produk.data') }}' + '?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                produkTbody.innerHTML = data.rows;
                produkPagination.innerHTML = data.pagination;
                pindahTbody.innerHTML = data.pindah;
                bindProdukPagination();
            } catch (e) {
                window.location.href = '{{ route('produk.index') }}' + '?' + params.toString();
            }
        }

        function bindProdukPagination() {
            produkPagination.querySelectorAll('a[href]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    fetchProduk(url.searchParams.get('page'));
                });
            });
        }

        produkForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchProduk(1);
        });

        let produkSearchTimer;
        document.querySelector('#produkFilterForm input[name="q"]').addEventListener('keyup', function() {
            clearTimeout(produkSearchTimer);
            produkSearchTimer = setTimeout(function() { fetchProduk(1); }, 500);
        });

        produkResetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            produkForm.reset();
            fetchProduk(1);
        });

        bindProdukPagination();
    </script>
@endpush
