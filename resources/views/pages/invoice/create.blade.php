@extends('layouts.app')
@section('title', 'Buat Tagihan Baru')
@section('content')
    <div class="p-4 md:p-6">
        <form action="{{ route('invoice.store') }}" method="POST" id="formInvoice">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kiri: Info Pelanggan & Item -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Card Info Pelanggan -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 transition-colors duration-200">
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                                <i class='bx bxs-user-detail text-gray-600 dark:text-blue-400'></i>
                                Info Pelanggan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">
                                        Pilih Pelanggan <span class="text-red-500">*</span>
                                    </label>
                                    <!-- Filter tipe -->
                                    <div class="flex gap-1 mb-2">
                                        <button type="button" data-tipe="" onclick="setFilterTipe(this)"
                                            class="filter-tipe-btn active px-2 py-1 text-xs rounded-lg border border-gray-300 dark:border-dark-600 bg-gray-600 text-white transition">
                                            Semua
                                        </button>
                                        <button type="button" data-tipe="individu" onclick="setFilterTipe(this)"
                                            class="filter-tipe-btn px-2 py-1 text-xs rounded-lg border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-600 transition">
                                            Individu
                                        </button>
                                        <button type="button" data-tipe="instansi" onclick="setFilterTipe(this)"
                                            class="filter-tipe-btn px-2 py-1 text-xs rounded-lg border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-600 transition">
                                            Instansi
                                        </button>
                                        <button type="button" data-tipe="mitra" onclick="setFilterTipe(this)"
                                            class="filter-tipe-btn px-2 py-1 text-xs rounded-lg border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-600 transition">
                                            Mitra
                                        </button>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="flex-1 min-w-0">
                                            <select name="pelanggan_id" id="pelanggan_id" required
                                                class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                            </select>
                                        </div>
                                        <button type="button" onclick="showTambahPelanggan()"
                                            class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white w-11 shrink-0 rounded-xl text-sm transition-all duration-200"
                                            title="Tambah Pelanggan Baru">
                                            <i class='bx bx-plus text-xl'></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">
                                        Piutang Sebelumnya
                                    </label>
                                    <div id="piutangInfo"
                                        class="mt-1 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl text-sm text-gray-500 dark:text-gray-400">
                                        Pilih pelanggan untuk cek piutang
                                    </div>
                                    <input type="hidden" name="exclude_piutang" id="exclude_piutang" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Item Tagihan -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 transition-colors duration-200">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                                    <i class='bx bxs-package text-gray-600 dark:text-blue-400'></i>
                                    Item Tagihan
                                </h3>
                                <div class="flex gap-2">
                                    <button type="button" onclick="showProdukModal()"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-xl text-sm flex items-center gap-1 transition-all duration-200">
                                        <i class='bx bx-package'></i> Tambah Produk
                                    </button>
                                    <button type="button" onclick="addLainnya()"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-xl text-sm flex items-center gap-1 transition-all duration-200">
                                        <i class='bx bx-plus'></i> Tambah Lainnya
                                    </button>
                                </div>
                            </div>

                            <div id="itemsContainer" class="space-y-3"></div>

                            <div id="emptyItems" class="text-center py-12 text-gray-400 dark:text-gray-500 text-sm">
                                <i class='bx bx-cart text-5xl mb-3 block'></i>
                                <p>Klik "Tambah Produk" atau "Tambah Lainnya" untuk menambahkan item</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Form & Ringkasan -->
                <div class="space-y-6">
                    <!-- Tanggal -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-6">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <i class='bx bx-calendar text-gray-600 dark:text-blue-400'></i>
                            Tanggal
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">
                                    Tanggal Tagihan <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">
                                    Jatuh Tempo
                                </label>
                                <input type="date" name="jatuh_tempo"
                                    value="{{ old('jatuh_tempo', date('Y-m-d', strtotime('+30 days'))) }}"
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-6">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <i class='bx bx-calculator text-gray-600 dark:text-blue-400'></i>
                            Ringkasan
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm py-2">
                                <span class="text-gray-600 dark:text-gray-400">Total Item</span>
                                <span id="totalItem" class="font-semibold text-gray-800 dark:text-white">Rp 0</span>
                            </div>
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Diskon</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="text" id="diskonInput" value="0"
                                        class="w-full pl-8 pr-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-right outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                    <input type="hidden" name="diskon" id="diskon" value="0">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Pajak</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="text" id="pajakInput" value="0"
                                        class="w-full pl-8 pr-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-right outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                    <input type="hidden" name="pajak" id="pajak" value="0">
                                </div>
                            </div>
                            <div class="border-t border-gray-100 dark:border-dark-700 pt-3">
                                <div class="flex justify-between text-sm py-1">
                                    <span class="text-gray-600 dark:text-gray-400">Piutang Sebelumnya</span>
                                    <span id="piutangSebelumnya"
                                        class="font-semibold text-orange-600 dark:text-orange-400">Rp 0</span>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 dark:border-dark-700 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-base text-gray-800 dark:text-white">Grand Total</span>
                                    <span id="grandTotal" class="font-bold text-xl text-gray-600 dark:text-blue-400">Rp
                                        0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bayar Langsung -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-6">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <i class='bx bx-money text-green-600 dark:text-green-400'></i>
                            Bayar Langsung <span
                                class="text-xs text-gray-400 dark:text-gray-500 font-normal">(Opsional)</span>
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Nominal
                                    Bayar</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="text" id="dibayarLangsungInput" value="0"
                                        class="w-full pl-8 pr-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-right outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                    <input type="hidden" name="dibayar_langsung" id="dibayar_langsung" value="0">
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Metode
                                    Pembayaran</label>
                                <select name="metode_pembayaran"
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="cash">💵 Cash</option>
                                    <option value="bank">🏦 Bank Transfer</option>
                                </select>
                            </div>
                            <div class="border-t border-gray-100 dark:border-dark-700 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Sisa Tagihan</span>
                                    <span id="sisaTagihan" class="font-bold text-xl text-red-600 dark:text-red-400">Rp
                                        0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <i class='bx bx-save text-lg'></i>
                        Simpan Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Pilih Produk -->
    <div id="modalProduk" tabindex="-1"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
        onclick="if(event.target===this)hideProdukModal()">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-lg p-6 max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bxs-package text-gray-600'></i> Pilih Produk
                </h3>
                <button onclick="hideProdukModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <div class="relative mb-3">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                <input type="text" id="searchProdukInput" placeholder="Cari produk..."
                    oninput="searchProduk(this.value)"
                    class="w-full pl-9 pr-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm outline-none focus:ring-2 focus:ring-gray-500/20 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
            </div>
            <div id="produkList" class="space-y-2 max-h-96 overflow-y-auto">
                @foreach ($produk as $p)
                    <div class="produk-item p-3 border border-gray-200 dark:border-dark-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900/30 cursor-pointer flex justify-between items-center transition-all duration-200"
                        data-id="{{ $p->id }}" data-nama="{{ $p->nama }}" data-harga="{{ $p->harga }}"
                        data-stok="{{ $p->stok }}" onclick="selectProduk(this)">
                        <div>
                            <p class="font-medium text-sm text-gray-800 dark:text-white">{{ $p->nama }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Stok: {{ $p->stok }}</p>
                        </div>
                        <span class="text-gray-600 dark:text-blue-400 font-semibold text-sm">Rp
                            {{ number_format($p->harga, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pelanggan -->
    <div id="modalPelangganBaru" tabindex="-1"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
        onclick="if(event.target===this)hidePelangganModal()">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 pb-0 shrink-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-user-plus text-green-600'></i> Tambah Pelanggan Baru
                </h3>
                <button onclick="hidePelangganModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('pelanggan.store') }}" method="POST" class="space-y-4 p-6 overflow-y-auto" id="formPelangganBaru">
                @csrf
                <input type="hidden" name="tipe" value="individu">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Nama <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="pelangganBaruNama" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-gray-500/20 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">No HP</label>
                    <input type="text" name="telepon" id="pelangganBaruHp"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-gray-500/20 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">Alamat</label>
                    <textarea name="alamat" id="pelangganBaruAlamat" rows="2"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-gray-500/20 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="hidePelangganModal()"
                        class="px-4 py-2 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 rounded-xl text-sm transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm transition">Simpan &
                        Pilih</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const allProdukData = @json($produk);
        let itemIndex = 0;

        function formatRupiah(angka) {
            if (isNaN(angka) || angka === null) angka = 0;
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function parseRupiah(str) {
            return parseInt(str.replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
        }

        function setupRupiahInput(inputId, hiddenId) {
            const input = document.getElementById(inputId);
            const hidden = document.getElementById(hiddenId);
            if (!input) return;

            input.addEventListener('input', function() {
                let value = parseRupiah(this.value);
                this.value = formatRupiah(value);
                if (hidden) hidden.value = value;
                calculateAll();
            });
        }

        function showProdukModal() {
            document.getElementById('modalProduk').classList.remove('hidden');
            document.getElementById('searchProdukInput').value = '';
            renderProdukList(allProdukData);
        }

        function hideProdukModal() {
            document.getElementById('modalProduk').classList.add('hidden');
        }

        function renderProdukList(data) {
            const container = document.getElementById('produkList');
            if (data.length === 0) {
                container.innerHTML =
                    '<p class="text-center text-gray-500 dark:text-gray-400 py-4">Tidak ada produk ditemukan</p>';
                return;
            }
            container.innerHTML = data.map(p => `
        <div class="produk-item p-3 border border-gray-200 dark:border-dark-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900/30 cursor-pointer flex justify-between items-center transition-all duration-200"
            data-id="${p.id}" data-nama="${p.nama}" data-harga="${p.harga}" data-stok="${p.stok}"
            onclick="selectProduk(this)">
            <div>
                <p class="font-medium text-sm text-gray-800 dark:text-white">${p.nama}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Stok: ${p.stok}</p>
            </div>
            <span class="text-gray-600 dark:text-blue-400 font-semibold text-sm">Rp ${formatRupiah(p.harga)}</span>
        </div>
    `).join('');
        }

        function searchProduk(q) {
            const filtered = allProdukData.filter(p => p.nama.toLowerCase().includes(q.toLowerCase()));
            renderProdukList(filtered);
        }

        function selectProduk(el) {
            const id = el.dataset.id;
            const nama = el.dataset.nama;
            const harga = parseFloat(el.dataset.harga) || 0;
            const stok = parseInt(el.dataset.stok);

            if (stok <= 0) {
                Swal.fire('Stok Habis', 'Produk ini sedang tidak tersedia', 'error');
                return;
            }

            const existing = document.querySelector(`.item-row input[name$="[produk_id]"][value="${id}"]`);
            if (existing) {
                Swal.fire('Produk Sudah Ada', 'Produk "' + nama + '" sudah ditambahkan. Silakan ubah qty jika perlu.', 'warning');
                hideProdukModal();
                return;
            }

            addItem('produk', id, nama, harga, 1, stok);
            hideProdukModal();
        }

        function addLainnya() {
            addItem('lainnya', null, '', 0, 1, null);
        }

        function addItem(tipe, produkId, nama, harga, qty, maxStok) {
            harga = parseFloat(harga) || 0;
            qty = parseInt(qty) || 1;
            document.getElementById('emptyItems').style.display = 'none';

            const container = document.getElementById('itemsContainer');
            const maxAttr = maxStok ? `max="${maxStok}"` : '';
            const stokInfo = maxStok ? `<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Stok: ${maxStok}</div>` : '';

            const html = `
        <div class="item-row bg-gray-50 dark:bg-dark-700/50 rounded-xl p-4 border border-gray-200 dark:border-dark-700" data-index="${itemIndex}" data-stok="${maxStok || ''}">
            <input type="hidden" name="items[${itemIndex}][tipe]" value="${tipe}">
            ${tipe === 'produk' ? `<input type="hidden" name="items[${itemIndex}][produk_id]" value="${produkId}">` : ''}
            <div class="grid grid-cols-12 gap-2 md:gap-3 items-end">
                <div class="col-span-12 md:col-span-5">
                    <label class="text-xs text-gray-500 dark:text-gray-400">${tipe === 'produk' ? 'Produk' : 'Keterangan'}</label>
                    ${tipe === 'produk'
                        ? `<input type="text" value="${nama}" readonly class="w-full border border-gray-200 dark:border-dark-700 rounded-lg px-2 py-2 text-sm mt-1 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                                       ${stokInfo}`
                        : `<input type="text" name="items[${itemIndex}][keterangan]" placeholder="Isi keterangan..." class="w-full border border-gray-200 dark:border-dark-700 rounded-lg px-2 py-2 text-sm mt-1 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300" required>`
                    }
                </div>
                <div class="col-span-4 md:col-span-2">
                    <label class="text-xs text-gray-500 dark:text-gray-400">Qty</label>
                    <input type="number" name="items[${itemIndex}][qty]" class="item-qty w-full border border-gray-200 dark:border-dark-700 rounded-lg px-2 py-2 text-sm mt-1 text-center bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300" value="${qty}" min="1" ${maxAttr} required oninput="validateQty(this); calculateAll();">
                </div>
                <div class="col-span-4 md:col-span-3">
                    <label class="text-xs text-gray-500 dark:text-gray-400">Harga</label>
                    <input type="text" name="items[${itemIndex}][harga]" class="item-harga w-full border border-gray-200 dark:border-dark-700 rounded-lg px-2 py-2 text-sm mt-1 text-right bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300" value="${formatRupiah(harga)}" min="0" required oninput="formatHarga(this); calculateAll();">
                </div>
                <div class="col-span-4 md:col-span-2 flex items-center gap-1">
                    <div class="flex-1 min-w-0">
                        <label class="text-xs text-gray-500 dark:text-gray-400">Subtotal</label>
                        <div class="item-subtotal text-sm font-semibold mt-1 text-gray-600 dark:text-blue-400 truncate">Rp ${formatRupiah(harga * qty)}</div>
                    </div>
                    <button type="button" onclick="this.closest('.item-row').remove(); calculateAll(); checkEmpty();" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg text-sm transition shrink-0 mt-4">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>
        </div>
    `;
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
            calculateAll();
        }

        function formatHarga(input) {
            let value = parseRupiah(input.value);
            input.value = formatRupiah(value);
            calculateAll();
        }

        function validateQty(input) {
            const maxStok = input.closest('.item-row').dataset.stok;
            if (maxStok && parseInt(input.value) > parseInt(maxStok)) {
                input.value = maxStok;
                Swal.fire('Stok Terbatas', `Maksimal qty adalah ${maxStok}`, 'warning');
            }
        }

        function checkEmpty() {
            if (document.querySelectorAll('.item-row').length === 0) {
                document.getElementById('emptyItems').style.display = 'block';
            }
        }

        function renderPiutangInfo(piutang) {
            const exclude = document.getElementById('exclude_piutang').value === '1';
            const piutangEl = document.getElementById('piutangSebelumnya');
            const infoEl = document.getElementById('piutangInfo');

            if (piutang > 0 && !exclude) {
                piutangEl.dataset.value = piutang;
                piutangEl.textContent = 'Rp ' + formatRupiah(piutang);
                infoEl.innerHTML =
                    `<div class="flex items-center justify-between">
                            <span class="text-orange-700 dark:text-orange-400">Piutang: Rp ${formatRupiah(piutang)}</span>
                            <button type="button" onclick="togglePiutang()" class="text-orange-600 hover:text-orange-800 dark:hover:text-orange-300 p-1 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/40 transition" title="Keluarkan piutang dari tagihan ini">
                                <i class='bx bx-x text-lg'></i>
                            </button>
                        </div>`;
                infoEl.className =
                    'mt-1 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-xl text-sm border border-orange-200 dark:border-orange-800/30';
            } else if (piutang > 0 && exclude) {
                piutangEl.dataset.value = 0;
                piutangEl.textContent = 'Rp 0';
                infoEl.innerHTML =
                    `<div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400 line-through">Piutang: Rp ${formatRupiah(piutang)}</span>
                            <button type="button" onclick="togglePiutang()" class="text-gray-600 hover:text-gray-800 dark:hover:text-blue-300 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900/40 transition" title="Ikutkan piutang ke tagihan ini">
                                <i class='bx bx-undo text-lg'></i>
                            </button>
                        </div>`;
                infoEl.className =
                    'mt-1 p-3 bg-gray-100 dark:bg-dark-700/50 rounded-xl text-sm border border-gray-200 dark:border-dark-700';
            } else {
                piutangEl.dataset.value = 0;
                piutangEl.textContent = 'Rp 0';
                infoEl.innerHTML = '<span class="text-green-700 dark:text-green-400">Tidak ada piutang</span>';
                infoEl.className =
                    'mt-1 p-3 bg-green-50 dark:bg-green-900/20 rounded-xl text-sm border border-green-200 dark:border-green-800/30';
            }
        }

        function togglePiutang() {
            const el = document.getElementById('exclude_piutang');
            el.value = el.value === '1' ? '0' : '1';
            const pelangganId = document.getElementById('pelanggan_id').value;
            if (pelangganId) {
                fetch(`/invoice/piutang/${pelangganId}`)
                    .then(res => res.json())
                    .then(data => {
                        renderPiutangInfo(data.total_piutang || 0);
                        calculateAll();
                    });
            }
        }

        function calculateAll() {
            let totalItem = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
                const hargaInput = row.querySelector('.item-harga')?.value;
                const harga = parseRupiah(hargaInput) || 0;
                const subtotal = qty * harga;
                const subEl = row.querySelector('.item-subtotal');
                if (subEl) subEl.textContent = 'Rp ' + formatRupiah(subtotal);
                totalItem += subtotal;
            });

            const diskon = parseRupiah(document.getElementById('diskonInput')?.value) || 0;
            const pajak = parseRupiah(document.getElementById('pajakInput')?.value) || 0;
            const exclude = document.getElementById('exclude_piutang')?.value === '1';
            const piutang = exclude ? 0 : (parseFloat(document.getElementById('piutangSebelumnya')?.dataset.value) || 0);
            const grandTotal = totalItem - diskon + pajak + piutang;
            const dibayar = parseRupiah(document.getElementById('dibayarLangsungInput')?.value) || 0;
            const sisa = grandTotal - dibayar;

            document.getElementById('totalItem').textContent = 'Rp ' + formatRupiah(totalItem);
            document.getElementById('grandTotal').textContent = 'Rp ' + formatRupiah(grandTotal);
            document.getElementById('sisaTagihan').textContent = 'Rp ' + formatRupiah(sisa);

            const sisaEl = document.getElementById('sisaTagihan');
            sisaEl.className = sisa <= 0 ? 'font-bold text-xl text-green-600 dark:text-green-400' :
                'font-bold text-xl text-red-600 dark:text-red-400';
        }

        function showTambahPelanggan() {
            document.getElementById('modalPelangganBaru').classList.remove('hidden');
        }

        function hidePelangganModal() {
            document.getElementById('modalPelangganBaru').classList.add('hidden');
        }

        document.getElementById('formPelangganBaru').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('{{ route('pelanggan.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(() => {
                    this.submit();
                });
        });

        setupRupiahInput('diskonInput', 'diskon');
        setupRupiahInput('pajakInput', 'pajak');
        setupRupiahInput('dibayarLangsungInput', 'dibayar_langsung');

        document.getElementById('formInvoice').addEventListener('submit', function(e) {
            const items = document.querySelectorAll('.item-row');
            if (items.length === 0) {
                e.preventDefault();
                Swal.fire('Error', 'Tambahkan minimal 1 item', 'error');
                return;
            }
            const btn = e.target.querySelector('button[type=submit]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class=\'bx bx-loader-alt bx-spin text-lg\'></i> Menyimpan...';
            }
        });

        $(document).ready(function() {
            let autoItemIndex = -1;
            let filterTipe = ''; // '' = semua

            // ===== FILTER TIPE BUTTON =====
            window.setFilterTipe = function(btn) {
                filterTipe = btn.dataset.tipe;
                document.querySelectorAll('.filter-tipe-btn').forEach(b => {
                    b.classList.remove('active', 'bg-gray-600', 'text-white');
                    b.classList.add('bg-white', 'dark:bg-dark-700', 'text-gray-600', 'dark:text-gray-300');
                });
                btn.classList.add('active', 'bg-gray-600', 'text-white');
                btn.classList.remove('bg-white', 'dark:bg-dark-700', 'text-gray-600', 'dark:text-gray-300');
                // Reset & buka ulang Select2
                $('#pelanggan_id').val(null).trigger('change');
                $('#pelanggan_id').select2('open');
            };

            // ===== SELECT2 AJAX =====
            function initSelect2() {
                $('#pelanggan_id').select2({
                    placeholder: '-- Ketik nama / no HP pelanggan --',
                    allowClear: true,
                    minimumInputLength: 0,
                    width: '100%',
                    dropdownParent: $('body'),
                    theme: document.documentElement.classList.contains('dark-mode') ? 'classic' : 'default',
                    ajax: {
                        url: '{{ route("pelanggan.search") }}',
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return { q: params.term || '', tipe: filterTipe };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results.map(function(p) {
                                    const badge = p.tipe === 'individu' ? '👤' : p.tipe === 'instansi' ? '🏢' : '🤝';
                                    const status = p.status === 'off' ? ' ⚠️ Non-aktif' : '';
                                    return {
                                        id: p.id,
                                        text: badge + ' ' + p.nama + ' (' + p.tipe.charAt(0).toUpperCase() + p.tipe.slice(1) + ')' + (p.telepon ? ' - ' + p.telepon : '') + status,
                                        paket_nama: p.paket_nama,
                                        paket_harga: p.paket_harga,
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    templateResult: function(data) {
                        if (data.loading) return data.text;
                        return $('<span>' + data.text + '</span>');
                    }
                });
            }

            initSelect2();

            window.addEventListener('themeChanged', function(e) {
                $('#pelanggan_id').select2('destroy');
                initSelect2();
            });

            // ===== PRE-FILL dari URL ?pelanggan_id=X =====
            const urlParams = new URLSearchParams(window.location.search);
            const prefilPelangganId = urlParams.get('pelanggan_id');
            if (prefilPelangganId) {
                fetch('{{ route("pelanggan.search") }}?q=&tipe=&id=' + prefilPelangganId, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    const p = data.results && data.results[0];
                    if (!p) return;
                    const badge = p.tipe === 'individu' ? '👤' : p.tipe === 'instansi' ? '🏢' : '🤝';
                    const label = badge + ' ' + p.nama + ' (' + p.tipe.charAt(0).toUpperCase() + p.tipe.slice(1) + ')' + (p.telepon ? ' - ' + p.telepon : '');
                    const option = new Option(label, p.id, true, true);
                    option.dataset = { paket_nama: p.paket_nama, paket_harga: p.paket_harga };
                    // Inject ke Select2
                    $('#pelanggan_id').append(option).trigger('change');
                    // Simpan data paket ke select2 data agar on('change') bisa baca
                    $('#pelanggan_id').data('prefill', p);
                });
            }

            // ===== ON CHANGE PELANGGAN =====
            $('#pelanggan_id').on('change', function() {
                const pelangganId = this.value;
                const piutangInfo = document.getElementById('piutangInfo');
                const selected = $(this).select2('data')[0];
                // Paket bisa dari select2 data atau dari prefill cache
                const prefill = $('#pelanggan_id').data('prefill');
                const paketNama  = selected?.paket_nama  || (prefill?.id == pelangganId ? prefill?.paket_nama  : '') || '';
                const paketHarga = parseFloat(selected?.paket_harga || (prefill?.id == pelangganId ? prefill?.paket_harga : 0)) || 0;
                // Hapus prefill cache setelah dipakai sekali
                if (prefill?.id == pelangganId) $('#pelanggan_id').removeData('prefill');

                if (autoItemIndex >= 0) {
                    const oldRow = document.querySelector('.item-row[data-auto="true"]');
                    if (oldRow) oldRow.remove();
                    autoItemIndex = -1;
                }

                if (!pelangganId) {
                    piutangInfo.innerHTML = 'Pilih pelanggan untuk cek piutang';
                    piutangInfo.className = 'mt-1 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl text-sm text-gray-500 dark:text-gray-400';
                    document.getElementById('piutangSebelumnya').textContent = 'Rp 0';
                    document.getElementById('piutangSebelumnya').dataset.value = 0;
                    calculateAll();
                    return;
                }

                if (paketNama) {
                    const idx = itemIndex;
                    autoItemIndex = idx;
                    addItem('lainnya', null, '', paketHarga, 1, null);
                    const newRow = document.querySelector(`.item-row[data-index="${idx}"]`);
                    if (newRow) {
                        newRow.dataset.auto = 'true';
                        const ketInput = newRow.querySelector('input[name="items[' + idx + '][keterangan]"]');
                        if (ketInput) ketInput.value = 'WiFi - ' + paketNama;
                    }
                }

                fetch(`/invoice/piutang/${pelangganId}`)
                    .then(res => res.json())
                    .then(data => {
                        const piutang = data.total_piutang || 0;
                        document.getElementById('exclude_piutang').value = '0';
                        renderPiutangInfo(piutang);
                        calculateAll();
                    });
            });
        });
    </script>
@endpush