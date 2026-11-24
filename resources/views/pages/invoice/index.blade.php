@extends('layouts.app')
@section('title', 'Daftar Invoice')
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bxs-receipt text-gray-600 dark:text-blue-400 text-2xl'></i>
                    Daftar Tagihan
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola semua tagihan pelanggan</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button data-modal-target="modalPrintTagihan" data-modal-toggle="modalPrintTagihan"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-printer text-lg'></i>
                    <span>Print Tagihan</span>
                </button>
                <a href="{{ route('invoice.create') }}"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-plus text-lg'></i>
                    <span>Buat Tagihan</span>
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <form method="GET" action="{{ route('invoice.index') }}" id="invoiceFilterForm" class="flex flex-wrap items-end gap-4">
                <!-- Tanggal Mulai -->
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Dari Tanggal
                    </label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai', $tanggalMulai) }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>

                <!-- Tanggal Akhir -->
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Sampai Tanggal
                    </label>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir', $tanggalAkhir) }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>

                <!-- Status -->
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-check-circle text-xs mr-1'></i> Status
                    </label>
                    <select name="status"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="cicilan" {{ request('status') == 'cicilan' ? 'selected' : '' }}>Cicilan/Piutang
                        </option>
                    </select>
                </div>

                <!-- Jenis Pelanggan -->
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-group text-xs mr-1'></i> Jenis Pelanggan
                    </label>
                    <select name="tipe_pelanggan"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">Semua Jenis</option>
                        <option value="individu" {{ request('tipe_pelanggan') == 'individu' ? 'selected' : '' }}>Individu
                        </option>
                        <option value="instansi" {{ request('tipe_pelanggan') == 'instansi' ? 'selected' : '' }}>Instansi
                        </option>
                        <option value="mitra" {{ request('tipe_pelanggan') == 'mitra' ? 'selected' : '' }}>Mitra</option>
                    </select>
                </div>

                <!-- Penanggung Jawab -->
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-user-check text-xs mr-1'></i> Penanggung Jawab
                    </label>
                    <select name="penanggung_jawab"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">Semua PJ</option>
                        @foreach ($penanggungJawabList as $pj)
                            <option value="{{ $pj->id }}" {{ request('penanggung_jawab') == $pj->id ? 'selected' : '' }}>{{ $pj->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pencarian -->
                <div class="flex-[2] min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-search text-xs mr-1'></i> Cari
                    </label>
                    <div class="relative">
                        <i
                            class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="No invoice / nama pelanggan..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <button type="button" id="invoiceResetBtn"
                        class="flex items-center gap-1.5 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                        <i class='bx bx-reset'></i> Reset
                    </button>
                </div>
            </form>

            {{-- Info hasil filter --}}
            @if (request('status') || request('tipe_pelanggan') || request('q') || request('penanggung_jawab') || request('tanggal_mulai') || request('tanggal_akhir'))
                <div
                    class="mt-4 pt-3 border-t border-gray-100 dark:border-dark-700 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class='bx bx-info-circle'></i>
                    <span>Filter aktif:</span>
                    @if (request('tanggal_mulai') || request('tanggal_akhir'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                            Periode: {{ request('tanggal_mulai', $tanggalMulai) }} s/d {{ request('tanggal_akhir', $tanggalAkhir) }}
                        </span>
                    @endif
                    @if (request('status'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                            Status: {{ request('status') == 'lunas' ? 'Lunas' : 'Cicilan/Piutang' }}
                        </span>
                    @endif
                    @if (request('tipe_pelanggan'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                            Jenis: {{ ucfirst(request('tipe_pelanggan')) }}
                        </span>
                    @endif
                    @if (request('q'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                            Cari: "{{ request('q') }}"
                        </span>
                    @endif
                    @if (request('penanggung_jawab'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                            PJ: {{ $penanggungJawabList->firstWhere('id', request('penanggung_jawab'))->nama ?? '-' }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Tabel Invoice -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-16">#</th>
                            <th class="px-4 py-3 text-left font-semibold">No Nota</th>
                            <th class="px-4 py-3 text-left font-semibold">Pelanggan</th>
                            <th class="px-4 py-3 text-center font-semibold hidden sm:table-cell">Tanggal</th>
                            <th class="px-4 py-3 text-center font-semibold hidden md:table-cell">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right font-semibold">Total</th>
                            <th class="px-4 py-3 text-right font-semibold hidden lg:table-cell">Dibayar</th>
                            <th class="px-4 py-3 text-right font-semibold">Sisa</th>
                            <th class="px-4 py-3 text-center font-semibold hidden xl:table-cell">Status</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceTbody" class="divide-y dark:divide-dark-700">
                        @include('pages.invoice.partials.rows')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="invoicePagination" class="p-4 border-t border-gray-100 dark:border-dark-700">
                @include('pages.invoice.partials.pagination')
            </div>
        </div>
    </div>
@endsection
    <!-- Modal Print Tagihan -->
    <div id="modalPrintTagihan" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-lg p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-printer text-green-600 dark:text-green-400 text-xl'></i>
                    Print Tagihan
                </h3>
                <button data-modal-hide="modalPrintTagihan"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('laporan.cetak-tagihan') }}" method="GET" target="_blank" class="space-y-4">
                <input type="hidden" name="type" id="exportType" value="pdf">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                            <i class='bx bx-calendar text-xs mr-1'></i> Dari Tanggal
                        </label>
                        <input type="date" name="tanggal_mulai"
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                            <i class='bx bx-calendar text-xs mr-1'></i> Sampai Tanggal
                        </label>
                        <input type="date" name="tanggal_akhir"
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                            <i class='bx bx-check-circle text-xs mr-1'></i> Status
                        </label>
                        <select name="status"
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            <option value="cicilan" selected>Cicilan (Piutang)</option>
                            <option value="semua">Semua Status</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                            <i class='bx bx-group text-xs mr-1'></i> Jenis Pelanggan
                        </label>
                        <select name="tipe"
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            <option value="semua">Semua Jenis</option>
                            <option value="individu">Individu</option>
                            <option value="instansi">Instansi</option>
                            <option value="mitra">Mitra</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                            <i class='bx bx-user-check text-xs mr-1'></i> Penanggung Jawab
                        </label>
                        <select name="penanggung_jawab"
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            <option value="">Semua PJ</option>
                            @foreach ($penanggungJawabList as $pj)
                                <option value="{{ $pj->id }}">{{ $pj->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" data-modal-hide="modalPrintTagihan"
                        class="px-4 py-2 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium transition">Batal</button>
                    <button type="submit" onclick="document.getElementById('exportType').value='pdf'"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium flex items-center gap-2 transition shadow-sm hover:shadow-md">
                        <i class='bx bx-file'></i> PDF
                    </button>
                    <button type="submit" onclick="document.getElementById('exportType').value='excel'"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm font-medium flex items-center gap-2 transition shadow-sm hover:shadow-md">
                        <i class='bx bx-spreadsheet'></i> Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Info Pajak -->
    <div id="modalPajak" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
        onclick="if(event.target===this)document.getElementById('modalPajak').classList.add('hidden')">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-calculator text-amber-600'></i>
                    Info Pajak
                </h3>
                <button onclick="document.getElementById('modalPajak').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <div class="space-y-3 text-sm" id="pajakContent">
                <p class="text-gray-500 dark:text-gray-400 text-xs" id="pajakNota"></p>
                <div class="bg-gray-50 dark:bg-dark-700/50 rounded-xl p-4 space-y-2">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>Harga Paket</span>
                        <span class="font-semibold text-gray-800 dark:text-white" id="pajakTotal">Rp 0</span>
                    </div>
                    <hr class="border-gray-200 dark:border-dark-700">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>DPP (Total ÷ 1,11)</span>
                        <span class="font-semibold text-gray-800 dark:text-white" id="pajakDpp">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>PPN (Total - DPP)</span>
                        <span class="font-semibold text-gray-800 dark:text-white" id="pajakPpn">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>BHP USO (DPP × 1,75%)</span>
                        <span class="font-semibold text-gray-800 dark:text-white" id="pajakBhp">Rp 0</span>
                    </div>
                    <hr class="border-gray-200 dark:border-dark-700">
                    <div class="flex justify-between text-amber-600 dark:text-amber-400 font-semibold">
                        <span>Proyeksi Pajak</span>
                        <span id="pajakProyeksi">Rp 0</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 italic">*Hanya estimasi, tidak mempengaruhi total tagihan</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showModalPajak(nota, total) {
                const dpp = total / 1.11;
                const ppn = total - dpp;
                const bhp = dpp * 0.0175;
                const proyeksi = bhp + ppn;

                document.getElementById('pajakNota').textContent = 'Nota: ' + nota;
                document.getElementById('pajakTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(total));
                document.getElementById('pajakDpp').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(dpp));
                document.getElementById('pajakPpn').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(ppn));
                document.getElementById('pajakBhp').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(bhp));
                document.getElementById('pajakProyeksi').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(proyeksi));

                document.getElementById('modalPajak').classList.remove('hidden');
                document.getElementById('modalPajak').classList.add('flex');
            }

            // SweetAlert confirm delete untuk invoice
            function confirmDeleteInvoice(formId) {
                Swal.fire({
                    title: 'Yakin hapus tagihan?',
                    text: "Data tagihan akan dihapus permanen!",
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

            // Tooltip sederhana dengan title bawaan sudah cukup
            document.addEventListener('DOMContentLoaded', function() {
                // Inisialisasi tooltip untuk elemen dengan title
                const tooltipElements = document.querySelectorAll('[title]');
                tooltipElements.forEach(el => {
                    el.addEventListener('mouseenter', (e) => {
                        // Native title sudah cukup, tidak perlu library tambahan
                    });
                });
            });

            // AJAX Filter & Search
            const invoiceForm = document.getElementById('invoiceFilterForm');
            const invoiceTbody = document.getElementById('invoiceTbody');
            const invoicePagination = document.getElementById('invoicePagination');
            const invoiceResetBtn = document.getElementById('invoiceResetBtn');

            async function fetchInvoices(page = null) {
                const params = new URLSearchParams(new FormData(invoiceForm));
                if (page) params.set('page', page);

                try {
                    const res = await fetch('{{ route('invoice.data') }}' + '?' + params.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    invoiceTbody.innerHTML = data.rows;
                    invoicePagination.innerHTML = data.pagination;
                    bindInvoicePagination();
                } catch (e) {
                    window.location.href = '{{ route('invoice.index') }}' + '?' + params.toString();
                }
            }

            function bindInvoicePagination() {
                invoicePagination.querySelectorAll('a[href]').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(link.href);
                        fetchInvoices(url.searchParams.get('page'));
                    });
                });
            }

            invoiceForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchInvoices(1);
            });

            let invoiceSearchTimer;
            document.querySelector('#invoiceFilterForm input[name="q"]').addEventListener('keyup', function() {
                clearTimeout(invoiceSearchTimer);
                invoiceSearchTimer = setTimeout(function() { fetchInvoices(1); }, 500);
            });

            invoiceResetBtn.addEventListener('click', function() {
                invoiceForm.reset();
                fetchInvoices(1);
            });

            bindInvoicePagination();
        </script>
    @endpush
