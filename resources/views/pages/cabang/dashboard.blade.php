@extends('layouts.app')
@section('title', 'Dashboard Cabang - ' . $cabang->nama)
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bxs-buildings text-gray-600 dark:text-blue-400'></i>
                Dashboard Cabang: {{ $cabang->nama }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manajemen stok produk cabang</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700">
            <div class="flex items-center gap-3">
                <div class="bg-gray-100 dark:bg-gray-900/30 p-3 rounded-xl">
                    <i class='bx bxs-package text-gray-600 dark:text-blue-400 text-xl'></i>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalProduk }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-xl">
                    <i class='bx bxs-layer text-green-600 dark:text-green-400 text-xl'></i>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Total Stok</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $totalStok }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-4 border border-gray-100 dark:border-dark-700">
            <div class="flex items-center gap-3">
                <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-xl">
                    <i class='bx bxs-x-circle text-red-600 dark:text-red-400 text-xl'></i>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Stok Habis</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $totalHabis }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
        <div class="p-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bxs-package text-gray-600 dark:text-blue-400'></i>
                Stok Produk Cabang
            </h3>
            <form method="GET" id="cabangSearchForm" class="relative w-full sm:w-64">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">#</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Produk</th>
                        <th class="px-4 py-3 text-center font-semibold">Stok</th>
                        <th class="px-4 py-3 text-center font-semibold hidden sm:table-cell">Status</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="cabangTbody" class="divide-y dark:divide-dark-700">
                    @include('pages.cabang.partials.dashboard-rows')
                </tbody>
            </table>
        </div>
        <div id="cabangPagination" class="border-t border-gray-100 dark:border-dark-700">
            @include('pages.cabang.partials.dashboard-pagination')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewBiaya(id) {
        const input = document.querySelector(`input[name="jumlah"][oninput="previewBiaya(${id})"]`);
        const el = document.getElementById('biayaPreview' + id);
        if (!input || !el) return;
        const harga = parseFloat(input.dataset.harga) || 0;
        const jumlah = parseInt(input.value) || 0;
        const total = harga * jumlah;
        el.textContent = total > 0
            ? 'Kas keluar: Rp ' + new Intl.NumberFormat('id-ID').format(total)
            : 'Harga beli belum diisi (biaya tidak tercatat di kas)';
    }

    // AJAX Pencarian
    const cabangForm = document.getElementById('cabangSearchForm');
    const cabangTbody = document.getElementById('cabangTbody');
    const cabangPagination = document.getElementById('cabangPagination');

    async function fetchCabangProduk(page = null) {
        const params = new URLSearchParams(new FormData(cabangForm));
        if (page) params.set('page', page);

        try {
            const res = await fetch('{{ route('cabang.dashboard.data') }}' + '?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            cabangTbody.innerHTML = data.rows;
            cabangPagination.innerHTML = data.pagination;
            bindCabangPagination();
        } catch (e) {
            window.location.href = '{{ route('cabang.dashboard') }}' + '?' + params.toString();
        }
    }

    function bindCabangPagination() {
        cabangPagination.querySelectorAll('a[href]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(link.href);
                fetchCabangProduk(url.searchParams.get('page'));
            });
        });
    }

    cabangForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchCabangProduk(1);
    });

    let cabangSearchTimer;
    document.querySelector('#cabangSearchForm input[name="q"]').addEventListener('keyup', function() {
        clearTimeout(cabangSearchTimer);
        cabangSearchTimer = setTimeout(function() { fetchCabangProduk(1); }, 500);
    });

    bindCabangPagination();
</script>
@endpush
