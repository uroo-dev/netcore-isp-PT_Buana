@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('content')
    <div class="p-4 md:p-6 space-y-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-cog text-gray-600 dark:text-blue-400 text-2xl'></i>
                Pengaturan
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pengaturan akun dan perusahaan</p>
        </div>

        <!-- Log Aktivitas -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div
                class="px-6 py-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-history text-gray-600 dark:text-blue-400 text-xl'></i>
                    Log Aktivitas
                    <span
                        class="text-xs bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 px-2 py-0.5 rounded-full"
                        id="logTotal">{{ $logs->total() }}</span>
                </h3>

                <!-- Filter & Search -->
                <form method="GET" action="{{ route('pengaturan.log') }}" id="logFilterForm" class="flex flex-wrap gap-2">
                    <div class="relative">
                        <i class='bx bx-filter-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm'></i>
                        <select name="tipe" id="logTipe"
                            class="pl-8 pr-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            <option value="semua">Semua Tipe</option>
                            <option value="aksi">Aksi</option>
                            <option value="login">Login/Logout</option>
                            <option value="request">Akses Halaman</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                    <select name="aksi" id="logAksi"
                        class="px-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                        <option value="semua">Semua Aksi</option>
                        <option value="create">Tambah</option>
                        <option value="update">Ubah</option>
                        <option value="delete">Hapus</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                        <option value="failed">Gagal Login</option>
                        <option value="access">Akses Halaman</option>
                        <option value="error">Error</option>
                    </select>
                    <select name="user_id" id="logUser"
                        class="px-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                        <option value="semua">Semua User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    <select name="model" id="logModel"
                        class="px-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                        <option value="semua">Semua Modul</option>
                        @foreach ($models as $model)
                            <option value="{{ $model }}">{{ $model }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="tanggal_mulai" id="logTanggalMulai"
                        class="px-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                    <input type="date" name="tanggal_akhir" id="logTanggalAkhir"
                        class="px-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                    <div class="relative">
                        <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm'></i>
                        <input type="text" name="q" id="logSearch" placeholder="Cari user, model, deskripsi..."
                            class="pl-8 pr-3 py-2 w-full sm:w-56 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-1 px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm transition">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <button type="button" id="logResetBtn"
                        class="inline-flex items-center justify-center gap-1 px-3 py-2 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-600 dark:text-gray-400 rounded-xl text-sm transition">
                        <i class='bx bx-reset'></i> Reset
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">User</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Modul</th>
                            <th class="px-4 py-3 text-left">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody id="logTbody" class="divide-y dark:divide-dark-700">
                        @include('pages.pengaturan.partials.log-rows')
                    </tbody>
                </table>
            </div>

            <div id="logPaginationWrap" class="px-6 py-4 border-t border-gray-100 dark:border-dark-700">
                @include('pages.pengaturan.partials.log-pagination')
            </div>
        </div>

        <!-- Informasi -->
        <div class="bg-gray-50 dark:bg-gray-900/20 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class='bx bx-info-circle text-gray-600 dark:text-blue-400 text-xl mt-0.5'></i>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-blue-300">Informasi Log Aktivitas</p>
                    <p class="text-xs text-gray-700 dark:text-blue-400 mt-1">
                        Log aktivitas mencatat seluruh aktivitas sistem: perubahan data (tambah/ubah/hapus), login &
                        logout, akses halaman, dan error. Gunakan filter di atas untuk mencari data dengan cepat.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const logForm = document.getElementById('logFilterForm');
        const logTbody = document.getElementById('logTbody');
        const logPaginationWrap = document.getElementById('logPaginationWrap');
        const logTotal = document.getElementById('logTotal');
        const logResetBtn = document.getElementById('logResetBtn');

        async function fetchLogs(page = null) {
            const params = new URLSearchParams(new FormData(logForm));
            if (page) params.set('page', page);

            try {
                const res = await fetch('{{ route('pengaturan.log.data') }}' + '?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                logTbody.innerHTML = data.rows;
                logPaginationWrap.innerHTML = data.pagination;
                logTotal.textContent = data.total;

                bindLogPagination();
            } catch (e) {
                // fallback: reload halaman
                window.location.href = '{{ route('pengaturan.log') }}' + '?' + params.toString();
            }
        }

        function bindLogPagination() {
            document.querySelectorAll('#logPagination a[href]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    fetchLogs(url.searchParams.get('page'));
                });
            });
        }

        logForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchLogs(1);
        });

        document.querySelectorAll('#logTipe, #logAksi, #logUser, #logModel, #logTanggalMulai, #logTanggalAkhir')
            .forEach(function(el) {
                el.addEventListener('change', function() {
                    fetchLogs(1);
                });
            });

        let searchTimer;
        logSearch.addEventListener('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() { fetchLogs(1); }, 500);
        });

        logResetBtn.addEventListener('click', function() {
            logForm.reset();
            fetchLogs(1);
        });

        bindLogPagination();
    </script>
@endpush