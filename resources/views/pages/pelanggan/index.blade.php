@extends('layouts.app')
@section('title', 'Manajemen Pelanggan & Mitra')
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Tab Navigation dengan Dark Mode Support -->
        <div class="mb-6 border-b border-gray-200 dark:border-dark-700 flex flex-col sm:flex-row justify-between gap-4">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="customerTabs"
                data-tabs-toggle="#customerTabContent" role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 rounded-t-lg text-gray-600 dark:text-blue-400 border-gray-600 dark:border-blue-400 active transition-all duration-200"
                        id="individu-tab" data-tabs-target="#individu" type="button" role="tab" aria-controls="individu"
                        aria-selected="true">
                        <i class='bx bx-user-circle text-lg'></i>
                        <span class="hidden sm:inline">Individu</span>
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-dark-600 transition-all duration-200"
                        id="instansi-tab" data-tabs-target="#instansi" type="button" role="tab"
                        aria-controls="instansi" aria-selected="false">
                        <i class='bx bx-building-house text-lg'></i>
                        <span class="hidden sm:inline">Instansi</span>
                    </button>
                </li>
                <li role="presentation">
                    <button
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-dark-600 transition-all duration-200"
                        id="mitra-tab" data-tabs-target="#mitra" type="button" role="tab" aria-controls="mitra"
                        aria-selected="false">
                        <i class='bx bx-store-alt text-lg'></i>
                        <span class="hidden sm:inline">Mitra</span>
                    </button>
                </li>
            </ul>

            <!-- Filter & Search -->
            <div class="flex items-center gap-2">
                <button id="toggleNonaktif" type="button"
                    class="px-3 py-2 text-sm border border-gray-200 dark:border-dark-700 rounded-xl hover:bg-gray-100 dark:hover:bg-dark-700 text-gray-600 dark:text-gray-400 flex items-center gap-2 transition-all duration-200"
                    title="Tampilkan/Sembunyikan pelanggan non aktif">
                    <i class='bx bx-filter'></i>
                    <span class="hidden sm:inline">Non Aktif</span>
                    <span id="nonaktifBadge"
                        class="hidden text-xs bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-1.5 py-0.5 rounded-full">0</span>
                </button>
                <div class="relative w-full sm:w-72">
                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                    <input type="text" id="searchCustomer" placeholder="Cari nama, telepon, alamat..."
                        class="w-full pl-9 pr-4 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div id="customerTabContent">
            <!-- TAB INDIVIDU -->
            <div class="hidden p-4 bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700"
                id="individu" role="tabpanel">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                        <i class='bx bx-user-circle text-gray-600 dark:text-blue-400'></i>
                        Daftar Pelanggan WiFi (Individu)
                        <span id="total-individu"
                            class="text-xs bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 px-2 py-0.5 rounded-full">{{ $individu->total() }}</span>
                    </h3>
                    <button onclick="openModal('modalTambahIndividu')"
                        class="border-2 border-gray-600 text-gray-600 dark:text-blue-400 dark:border-gray-500 hover:bg-gray-600 hover:text-white dark:hover:bg-gray-600 px-4 py-2 rounded-xl text-sm flex items-center gap-2 transition-all duration-200">
                        <i class='bx bx-plus'></i>Tambah Individu
                    </button>
                </div>

                <div class="overflow-x-auto -mx-4 px-4 relative">
                    <div id="loading-individu" class="hidden absolute inset-0 bg-white/70 dark:bg-dark-800/70 flex items-center justify-center z-10 rounded-xl">
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-sm">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            Memuat...
                        </div>
                    </div>
                    <table class="w-full text-sm" id="individuTable">
                        <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-700 dark:text-gray-300 border-b dark:border-dark-700">
                            <tr>
                                <th class="px-3 py-3 text-center w-20">Status</th>
                                <th class="px-3 py-3 text-left">Nama</th>
                                <th class="px-3 py-3 text-left hidden md:table-cell">No HP</th>
                                <th class="px-3 py-3 text-left hidden lg:table-cell">Alamat</th>
                                <th class="px-3 py-3 text-left hidden xl:table-cell">Paket</th>
                                <th class="px-3 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-individu" class="divide-y dark:divide-dark-700">
                            @include('pages.pelanggan.partials.individu-rows', ['items' => $individu])
                        </tbody>
                    </table>
                </div>
                <div id="pagination-individu" class="mt-4">{{ $individu->links() }}</div>
            </div>

            <!-- TAB INSTANSI -->
            <div class="hidden p-4 bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700"
                id="instansi" role="tabpanel">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                        <i class='bx bx-building-house text-gray-600 dark:text-blue-400'></i>
                        Daftar Pelanggan (Instansi)
                        <span id="total-instansi"
                            class="text-xs bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 px-2 py-0.5 rounded-full">{{ $instansi->total() }}</span>
                    </h3>
                    <button onclick="openModal('modalTambahInstansi')"
                        class="border-2 border-gray-600 text-gray-600 dark:text-blue-400 dark:border-gray-500 hover:bg-gray-600 hover:text-white dark:hover:bg-gray-600 px-4 py-2 rounded-xl text-sm flex items-center gap-2 transition-all duration-200">
                        <i class='bx bx-plus'></i>Tambah Instansi
                    </button>
                </div>
                <div class="overflow-x-auto -mx-4 px-4 relative">
                    <div id="loading-instansi" class="hidden absolute inset-0 bg-white/70 dark:bg-dark-800/70 flex items-center justify-center z-10 rounded-xl">
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-sm">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            Memuat...
                        </div>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-700 dark:text-gray-300 border-b dark:border-dark-700">
                            <tr>
                                <th class="px-3 py-3 text-center w-20">Status</th>
                                <th class="px-3 py-3 text-left">Nama</th>
                                <th class="px-3 py-3 text-left hidden md:table-cell">No HP</th>
                                <th class="px-3 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-instansi" class="divide-y dark:divide-dark-700">
                            @include('pages.pelanggan.partials.instansi-rows', ['items' => $instansi])
                        </tbody>
                    </table>
                </div>
                <div id="pagination-instansi" class="mt-4">{{ $instansi->links() }}</div>
            </div>

            <!-- TAB MITRA -->
            <div class="hidden p-4 bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700"
                id="mitra" role="tabpanel">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                        <i class='bx bx-store-alt text-gray-600 dark:text-blue-400'></i>
                        Daftar Mitra
                        <span id="total-mitra"
                            class="text-xs bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 px-2 py-0.5 rounded-full">{{ $mitra->total() }}</span>
                    </h3>
                    <button onclick="openModal('modalTambahMitra')"
                        class="border-2 border-gray-600 text-gray-600 dark:text-blue-400 dark:border-gray-500 hover:bg-gray-600 hover:text-white dark:hover:bg-gray-600 px-4 py-2 rounded-xl text-sm flex items-center gap-2 transition">
                        <i class='bx bx-plus'></i>Tambah Mitra
                    </button>
                </div>
                <div class="overflow-x-auto -mx-4 px-4 relative">
                    <div id="loading-mitra" class="hidden absolute inset-0 bg-white/70 dark:bg-dark-800/70 flex items-center justify-center z-10 rounded-xl">
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-sm">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            Memuat...
                        </div>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-700 dark:text-gray-300 border-b dark:border-dark-700">
                            <tr>
                                <th class="px-3 py-3 text-center w-20">Status</th>
                                <th class="px-3 py-3 text-left">Nama</th>
                                <th class="px-3 py-3 text-left hidden md:table-cell">No HP</th>
                                <th class="px-3 py-3 text-left hidden xl:table-cell">Paket WiFi</th>
                                <th class="px-3 py-3 text-right hidden xl:table-cell">Harga</th>
                                <th class="px-3 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-mitra" class="divide-y dark:divide-dark-700">
                            @include('pages.pelanggan.partials.mitra-rows', ['items' => $mitra])
                        </tbody>
                    </table>
                </div>
                <div id="pagination-mitra" class="mt-4">{{ $mitra->links() }}</div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH INDIVIDU -->
    <div id="modalTambahIndividu" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
        onclick="if(event.target===this)closeModal('modalTambahIndividu')">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 pb-0 shrink-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tambah Pelanggan Individu</h3>
                <button onclick="closeModal('modalTambahIndividu')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('pelanggan.store') }}" method="POST" class="space-y-4 p-6 overflow-y-auto">
                @csrf
                <input type="hidden" name="tipe" value="individu">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                    <input type="text" name="nama"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No HP</label>
                    <input type="text" name="telepon"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Koordinat</label>
                    <input type="text" name="koordinat" placeholder="-7.123,110.123"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Address</label>
                    <input type="text" name="ip_address"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paket WiFi</label>
                    <select name="paket_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                        <option value="">-- Pilih Paket --</option>
                        @foreach ($paketWifi ?? [] as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} - Rp
                                {{ number_format($p->harga, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penanggung Jawab</label>
                    <select name="penanggung_jawab_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                        <option value="">-- Pilih Penanggung Jawab --</option>
                        @foreach ($penanggungJawab ?? [] as $pj)
                            <option value="{{ $pj->id }}">{{ $pj->nama }} ({{ $pj->wilayah ?? 'All' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal('modalTambahIndividu')"
                        class="px-4 py-2 bg-gray-200 dark:bg-dark-700 rounded-xl text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-xl text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH INSTANSI -->
    <div id="modalTambahInstansi" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
        onclick="if(event.target===this)closeModal('modalTambahInstansi')">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 pb-0 shrink-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tambah Pelanggan Instansi</h3>
                <button onclick="closeModal('modalTambahInstansi')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('pelanggan.store') }}" method="POST" class="space-y-4 p-6 overflow-y-auto">
                @csrf
                <input type="hidden" name="tipe" value="instansi">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Instansi</label>
                    <input type="text" name="nama"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No HP</label>
                    <input type="text" name="telepon"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Koordinat</label>
                    <input type="text" name="koordinat" placeholder="-7.123,110.123"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Address</label>
                    <input type="text" name="ip_address"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paket WiFi</label>
                    <select name="paket_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                        <option value="">-- Pilih Paket --</option>
                        @foreach ($paketWifi ?? [] as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} - Rp
                                {{ number_format($p->harga, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penanggung Jawab</label>
                    <select name="penanggung_jawab_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                        <option value="">-- Pilih Penanggung Jawab --</option>
                        @foreach ($penanggungJawab ?? [] as $pj)
                            <option value="{{ $pj->id }}">{{ $pj->nama }} ({{ $pj->wilayah ?? 'All' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal('modalTambahInstansi')"
                        class="px-4 py-2 bg-gray-200 dark:bg-dark-700 rounded-xl text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-xl text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH MITRA -->
    <div id="modalTambahMitra" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
        onclick="if(event.target===this)closeModal('modalTambahMitra')">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 pb-0 shrink-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tambah Pelanggan Mitra</h3>
                <button onclick="closeModal('modalTambahMitra')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('pelanggan.store') }}" method="POST" class="space-y-4 p-6 overflow-y-auto">
                @csrf
                <input type="hidden" name="tipe" value="mitra">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Mitra</label>
                    <input type="text" name="nama"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No HP</label>
                    <input type="text" name="telepon"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paket WiFi</label>
                    <input type="text" name="paket_wifi_custom" placeholder="Ketik paket WiFi..."
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Paket</label>
                    <input type="text" name="harga_wifi_custom" placeholder="Rp 0"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2"
                        oninput="formatRupiah(this)">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal('modalTambahMitra')"
                        class="px-4 py-2 bg-gray-200 dark:bg-dark-700 rounded-xl text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-xl text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT UNIFIED -->
    <div id="modalEdit" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
        onclick="if(event.target===this)closeModal('modalEdit')">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 pb-0 shrink-0">
                <h3 id="modalEditTitle" class="text-lg font-semibold text-gray-800 dark:text-white">Edit Pelanggan</h3>
                <button onclick="closeModal('modalEdit')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form id="formEdit" method="POST" class="space-y-4 p-6 overflow-y-auto">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipe" id="edit_tipe">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                    <input type="text" name="nama" id="edit_nama"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No HP</label>
                    <input type="text" name="telepon" id="edit_telepon"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                    <textarea name="alamat" id="edit_alamat" rows="2"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2"></textarea>
                </div>
                <div id="editFieldKoordinat" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Koordinat</label>
                    <input type="text" name="koordinat" id="edit_koordinat"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div id="editFieldIp" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Address</label>
                    <input type="text" name="ip_address" id="edit_ip"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div id="editFieldPaket" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paket WiFi</label>
                    <select name="paket_id" id="edit_paket_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                        <option value="">-- Pilih Paket --</option>
                        @foreach ($paketWifi ?? [] as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} - Rp
                                {{ number_format($p->harga, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="editFieldPaketCustom" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paket WiFi</label>
                    <input type="text" name="paket_wifi_custom" id="edit_paket_wifi_custom"
                        placeholder="Ketik paket WiFi..."
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                </div>
                <div id="editFieldHargaCustom" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Paket</label>
                    <input type="text" name="harga_wifi_custom" id="edit_harga_wifi_custom" placeholder="Rp 0"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2"
                        oninput="formatRupiah(this)">
                </div>
                <div id="editFieldPj" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penanggung Jawab</label>
                    <select name="penanggung_jawab_id" id="edit_pj_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2">
                        <option value="">-- Pilih Penanggung Jawab --</option>
                        @foreach ($penanggungJawab ?? [] as $pj)
                            <option value="{{ $pj->id }}">{{ $pj->nama }} ({{ $pj->wilayah ?? 'All' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal('modalEdit')"
                        class="px-4 py-2 bg-gray-200 dark:bg-dark-700 rounded-xl text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-xl text-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ========== MODAL FUNCTIONS ==========
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; }
        }
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; }
        }

        // ========== FORMAT RUPIAH ==========
        function formatRupiah(input) {
            let val = input.value.replace(/[^0-9]/g, '');
            input.value = val === '' ? '' : 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
        }

        // ========== EDIT MODAL ==========
        function openEditModal(id, tipe, nama, telepon, alamat, koordinat, ip, paketId, pjId, paketCustom, hargaCustom) {
            const form = document.getElementById('formEdit');
            form.action = '/pelanggan/' + id;
            document.getElementById('edit_tipe').value = tipe;
            document.getElementById('edit_nama').value = nama || '';
            document.getElementById('edit_telepon').value = telepon || '';
            document.getElementById('edit_alamat').value = alamat || '';
            const isMitra = (tipe === 'mitra');
            document.getElementById('editFieldKoordinat').classList.toggle('hidden', isMitra);
            document.getElementById('editFieldIp').classList.toggle('hidden', isMitra);
            document.getElementById('editFieldPaket').classList.toggle('hidden', isMitra);
            document.getElementById('editFieldPaketCustom').classList.toggle('hidden', !isMitra);
            document.getElementById('editFieldHargaCustom').classList.toggle('hidden', !isMitra);
            document.getElementById('editFieldPj').classList.toggle('hidden', isMitra);
            if (!isMitra) {
                document.getElementById('edit_koordinat').value = koordinat || '';
                document.getElementById('edit_ip').value = ip || '';
                document.getElementById('edit_paket_id').value = paketId || '';
                document.getElementById('edit_pj_id').value = pjId || '';
            } else {
                document.getElementById('edit_paket_wifi_custom').value = paketCustom || '';
                const hc = document.getElementById('edit_harga_wifi_custom');
                hc.value = hargaCustom ? 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaCustom) : '';
            }
            const titleMap = { individu: 'Edit Pelanggan Individu', instansi: 'Edit Instansi', mitra: 'Edit Mitra' };
            document.getElementById('modalEditTitle').innerHTML = titleMap[tipe] || 'Edit Pelanggan';
            openModal('modalEdit');
        }

        // ========== DELETE ==========
        function confirmDelete(formId, nama) {
            Swal.fire({
                title: 'Yakin hapus?',
                html: `Data "<strong>${nama}</strong>" akan dihapus permanen!`,
                icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }

        // ========== AJAX FETCH TAB ==========
        const BASE_URL = '{{ url("/pelanggan/data") }}';
        let activeTab = 'individu';
        let searchQuery = { individu: '', instansi: '', mitra: '' };
        let showNonaktif = false;
        let searchTimer = null;

        function fetchTab(tipe, page, q) {
            if (q === undefined) q = searchQuery[tipe];
            const tbody      = document.getElementById('tbody-' + tipe);
            const pagination = document.getElementById('pagination-' + tipe);
            const loading    = document.getElementById('loading-' + tipe);
            const totalBadge = document.getElementById('total-' + tipe);

            if (loading) loading.classList.remove('hidden');

            const params = new URLSearchParams({ page: page || 1, q: q || '' });
            fetch(`${BASE_URL}/${tipe}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (tbody) tbody.innerHTML = data.rows;
                if (pagination) pagination.innerHTML = data.pagination;
                if (totalBadge) totalBadge.textContent = data.total;
                // Intercept pagination links agar AJAX bukan full-reload
                bindPaginationLinks(tipe);
                // Terapkan filter nonaktif ke rows baru
                applyNonaktifFilter(tipe);
            })
            .catch(() => {
                if (tbody) tbody.innerHTML = '<tr><td colspan="6" class="px-3 py-6 text-center text-red-400">Gagal memuat data</td></tr>';
            })
            .finally(() => {
                if (loading) loading.classList.add('hidden');
            });
        }

        function bindPaginationLinks(tipe) {
            const paginationEl = document.getElementById('pagination-' + tipe);
            if (!paginationEl) return;
            paginationEl.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page') || 1;
                    fetchTab(tipe, page, searchQuery[tipe]);
                });
            });
        }

        function applyNonaktifFilter(tipe) {
            const tbody = document.getElementById('tbody-' + tipe);
            if (!tbody) return;
            tbody.querySelectorAll('tr[data-status="off"]').forEach(row => {
                row.style.display = showNonaktif ? '' : 'none';
            });
            updateNonaktifBadge();
        }

        function updateNonaktifBadge() {
            const tbody = document.getElementById('tbody-' + activeTab);
            if (!tbody) return;
            const count = tbody.querySelectorAll('tr[data-status="off"]').length;
            const badge = document.getElementById('nonaktifBadge');
            if (badge) { badge.textContent = count; badge.classList.toggle('hidden', count === 0); }
        }

        // ========== TAB SWITCHING ==========
        function activateTab(tabName) {
            const targetPanel = document.getElementById(tabName);
            const targetBtn   = document.querySelector(`[data-tabs-target="#${tabName}"]`);
            if (!targetPanel || !targetBtn) return;

            document.querySelectorAll('[role="tabpanel"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[role="tab"]').forEach(btn => {
                btn.classList.remove('text-gray-600', 'border-gray-600', 'dark:text-blue-400', 'dark:border-blue-400', 'active');
                btn.classList.add('border-transparent');
                btn.setAttribute('aria-selected', 'false');
            });

            targetPanel.classList.remove('hidden');
            targetBtn.classList.remove('border-transparent');
            targetBtn.classList.add('text-gray-600', 'border-gray-600', 'dark:text-blue-400', 'dark:border-blue-400', 'active');
            targetBtn.setAttribute('aria-selected', 'true');

            activeTab = tabName;

            // Reset search input saat ganti tab
            const searchInput = document.getElementById('searchCustomer');
            if (searchInput) searchInput.value = searchQuery[tabName] || '';

            applyNonaktifFilter(tabName);
        }

        // ========== SEARCH ==========
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchCustomer');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const q = this.value.trim();
                    searchQuery[activeTab] = q;
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        fetchTab(activeTab, 1, q);
                    }, 350); // debounce 350ms
                });
            }

            // Toggle nonaktif
            const toggleBtn = document.getElementById('toggleNonaktif');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    showNonaktif = !showNonaktif;
                    this.classList.toggle('bg-gray-50', showNonaktif);
                    this.classList.toggle('dark:bg-gray-900/20', showNonaktif);
                    this.classList.toggle('text-gray-600', showNonaktif);
                    this.classList.toggle('dark:text-blue-400', showNonaktif);
                    applyNonaktifFilter(activeTab);
                });
            }

            // Tab button click
            document.querySelectorAll('[role="tab"]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const target = this.getAttribute('data-tabs-target');
                    if (!target) return;
                    const tabName = target.replace('#', '');
                    activateTab(tabName);
                    // Bind pagination links setelah tab aktif
                    bindPaginationLinks(tabName);
                });
            });

            // Baca ?tab= dari URL saat load
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab && ['individu', 'instansi', 'mitra'].includes(tab)) {
                activateTab(tab);
            } else {
                activateTab('individu');
            }

            // Bind pagination links untuk tab yang aktif saat load
            bindPaginationLinks(activeTab);

            // ESC tutup modal
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    ['modalTambahIndividu','modalTambahInstansi','modalTambahMitra','modalEdit'].forEach(id => {
                        const m = document.getElementById(id);
                        if (m && !m.classList.contains('hidden')) closeModal(id);
                    });
                }
            });
        });
    </script>
@endpush
