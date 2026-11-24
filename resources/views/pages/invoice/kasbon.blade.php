@extends('layouts.app')
@section('title', 'Kasbon / Piutang')
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bxs-credit-card text-orange-600 dark:text-orange-400 text-2xl'></i>
                    Kasbon / Piutang
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Tagihan yang masih dalam cicilan / belum lunas</p>
            </div>
            <a href="{{ route('invoice.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                <i class='bx bx-plus text-lg'></i>
                <span>Buat Tagihan Baru</span>
            </a>
        </div>

        <!-- Filter -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <form method="GET" action="{{ route('kasbon.index') }}" id="kasbonFilterForm" class="flex flex-wrap items-end gap-4">
                <!-- Jenis Pelanggan -->
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-group text-xs mr-1'></i> Jenis Pelanggan
                    </label>
                    <select name="tipe_pelanggan"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">Semua Jenis</option>
                        <option value="individu" {{ request('tipe_pelanggan') == 'individu' ? 'selected' : '' }}>Individu</option>
                        <option value="instansi" {{ request('tipe_pelanggan') == 'instansi' ? 'selected' : '' }}>Instansi</option>
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
                        <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
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
                    <button type="button" id="kasbonResetBtn"
                        class="flex items-center gap-1.5 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                        <i class='bx bx-reset'></i> Reset
                    </button>
                </div>
            </form>

            {{-- Info hasil filter --}}
            @if (request('tipe_pelanggan') || request('q') || request('penanggung_jawab'))
                <div
                    class="mt-4 pt-3 border-t border-gray-100 dark:border-dark-700 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class='bx bx-info-circle'></i>
                    <span>Filter aktif:</span>
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

        <!-- Tabel Kasbon -->
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
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kasbonTbody" class="divide-y dark:divide-dark-700">
                        @include('pages.invoice.partials.rows')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="kasbonPagination" class="p-4 border-t border-gray-100 dark:border-dark-700">
                @include('pages.invoice.partials.pagination')
            </div>
        </div>
    </div>

    <!-- Modal Info Pajak -->
    <div id="modalPajak" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
        onclick="if(event.target===this)this.classList.add('hidden')">
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
@endsection

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

            const modal = document.getElementById('modalPajak');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function confirmDeleteKasbon(formId) {
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

        // AJAX Filter & Search
        const kasbonForm = document.getElementById('kasbonFilterForm');
        const kasbonTbody = document.getElementById('kasbonTbody');
        const kasbonPagination = document.getElementById('kasbonPagination');
        const kasbonResetBtn = document.getElementById('kasbonResetBtn');

        async function fetchKasbon(page = null) {
            const params = new URLSearchParams(new FormData(kasbonForm));
            if (page) params.set('page', page);

            try {
                const res = await fetch('{{ route('kasbon.data') }}' + '?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                kasbonTbody.innerHTML = data.rows;
                kasbonPagination.innerHTML = data.pagination;
                bindKasbonPagination();
            } catch (e) {
                window.location.href = '{{ route('kasbon.index') }}' + '?' + params.toString();
            }
        }

        function bindKasbonPagination() {
            kasbonPagination.querySelectorAll('a[href]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    fetchKasbon(url.searchParams.get('page'));
                });
            });
        }

        kasbonForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchKasbon(1);
        });

        let kasbonSearchTimer;
        document.querySelector('#kasbonFilterForm input[name="q"]').addEventListener('keyup', function() {
            clearTimeout(kasbonSearchTimer);
            kasbonSearchTimer = setTimeout(function() { fetchKasbon(1); }, 500);
        });

        kasbonResetBtn.addEventListener('click', function() {
            kasbonForm.reset();
            fetchKasbon(1);
        });

        bindKasbonPagination();
    </script>
@endpush