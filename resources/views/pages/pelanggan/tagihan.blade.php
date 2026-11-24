@extends('layouts.app')
@section('title', 'Tagihan - ' . $pelanggan->nama)
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Tombol Navigasi -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <a href="{{ route('pelanggan.show', $pelanggan->id) }}"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-600 dark:hover:text-blue-400 transition-all duration-200 group">
                <i class='bx bx-arrow-back text-xl group-hover:-translate-x-1 transition-transform'></i>
                <span>Kembali ke Detail</span>
            </a>
            <a href="{{ route('invoice.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                <i class='bx bx-plus text-lg'></i>
                <span>Buat Tagihan Baru</span>
            </a>
        </div>

        <!-- Info Ringkas Pelanggan -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div class="p-5">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div
                        class="bg-gradient-to-br from-gray-500 to-gray-700 w-14 h-14 rounded-2xl flex items-center justify-center shadow-md">
                        <span class="text-white text-xl font-bold">{{ substr($pelanggan->nama, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-gray-800 dark:text-white">{{ $pelanggan->nama }}</h1>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class='bx bx-phone text-xs'></i> {{ $pelanggan->telepon ?? '-' }}
                            </p>
                            @if ($pelanggan->paket || $pelanggan->penanggungJawab)
                                <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                    <i class='bx bx-wifi text-xs'></i> {{ $pelanggan->paket?->nama ?? '-' }}
                                    <span class="mx-1">•</span>
                                    <i class='bx bx-user-check text-xs'></i> {{ $pelanggan->penanggungJawab?->nama ?? '-' }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <!-- Badge Status Pelanggan -->
                    <div class="flex-shrink-0">
                        @if ($pelanggan->status == 'on')
                            <span
                                class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-3 py-1.5 rounded-xl text-xs font-medium">
                                <i class='bx bx-check-circle text-xs'></i> Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-3 py-1.5 rounded-xl text-xs font-medium">
                                <i class='bx bx-x-circle text-xs'></i> Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Tagihan -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
            <div
                class="px-5 py-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-receipt text-gray-600 dark:text-blue-400'></i>
                    Daftar Tagihan
                    <span
                        class="text-xs bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded-full">{{ $invoices->total() }}</span>
                </h3>
                <!-- Search box untuk filter tagihan -->
                <div class="relative w-full sm:w-64">
                    <i
                        class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm'></i>
                    <input type="text" id="searchInvoice" placeholder="Cari tagihan..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-dark-700 rounded-xl bg-gray-50 dark:bg-dark-700/50 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 transition-colors">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="invoiceTable">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left w-16">#</th>
                            <th class="px-4 py-3 text-left">No Nota</th>
                            <th class="px-4 py-3 text-center hidden sm:table-cell">Tanggal</th>
                            <th class="px-4 py-3 text-center hidden md:table-cell">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-right hidden lg:table-cell">Dibayar</th>
                            <th class="px-4 py-3 text-right">Sisa</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-dark-700">
                        @forelse($invoices as $inv)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 font-medium">
                                    {{ $invoices->firstItem() + $loop->index }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('invoice.show', $inv->id) }}"
                                        class="font-mono text-xs font-semibold text-gray-600 dark:text-blue-400 hover:underline">
                                        {{ $inv->kode_invoice }}
                                    </a>
                                    <!-- Info mobile: tanggal -->
                                    <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $inv->tanggalJ }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center hidden sm:table-cell text-gray-600 dark:text-gray-400">
                                    {{ $inv->tanggalJ }}
                                </td>
                                <td class="px-4 py-3 text-center hidden md:table-cell">
                                    @if ($inv->jatuh_tempo && \Carbon\Carbon::parse($inv->jatuh_tempo)->isPast() && $inv->status != 'lunas')
                                        <span
                                            class="text-red-600 dark:text-red-400 font-medium">{{ $inv->jatuh_tempoJ ?? '-' }}</span>
                                    @else
                                        <span
                                            class="text-gray-600 dark:text-gray-400">{{ $inv->jatuh_tempoJ ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-800 dark:text-white">
                                    Rp {{ number_format($inv->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right hidden lg:table-cell text-green-600 dark:text-green-400">
                                    Rp {{ number_format($inv->dibayar, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-semibold {{ $inv->sisa > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    Rp {{ number_format($inv->sisa, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($inv->status === 'lunas')
                                        <span
                                            class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full text-xs font-medium">
                                            <i class='bx bx-check-circle text-xs'></i> Lunas
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-400 px-2.5 py-1 rounded-full text-xs font-medium">
                                            <i class='bx bx-time text-xs'></i> Cicilan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('invoice.show', $inv->id) }}"
                                            class="p-1.5 rounded-lg bg-gray-50 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-900/50 transition"
                                            title="Detail">
                                            <i class='bx bx-show text-sm'></i>
                                        </a>
                                        @if ($inv->status !== 'lunas')
                                            <a href="{{ route('invoice.bayar', $inv->id) }}"
                                                class="p-1.5 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/50 transition"
                                                title="Bayar">
                                                <i class='bx bx-money text-sm'></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('invoice.cetak', $inv->id) }}" target="_blank"
                                            class="p-1.5 rounded-lg bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/50 transition"
                                            title="Cetak PDF">
                                            <i class='bx bx-printer text-sm'></i>
                                        </a>
                                        @if(auth()->user()->role == 'owner')
                                        <button type="button"
                                            onclick="showModalPajak('{{ $inv->kode_invoice }}', {{ $inv->total }})"
                                            class="p-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 transition"
                                            title="Info Pajak">
                                            <i class='bx bx-calculator text-sm'></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class='bx bx-receipt text-4xl mb-2 block'></i>
                                    <p>Belum ada tagihan untuk pelanggan ini</p>
                                    <a href="{{ route('invoice.create') }}"
                                        class="inline-block mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline">
                                        Buat tagihan pertama →
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($invoices->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-dark-700">
                    {{ $invoices->withQueryString()->links() }}
                </div>
            @endif
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
                <button onclick="this.closest('#modalPajak').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <div class="space-y-3 text-sm">
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

            // Search functionality untuk tabel tagihan (tanpa mengubah fungsi route)
            document.getElementById('searchInvoice')?.addEventListener('keyup', function() {
                let keyword = this.value.toLowerCase();
                let rows = document.querySelectorAll('#invoiceTable tbody tr');
                let hasResults = false;

                rows.forEach(row => {
                    // Skip jika row adalah pesan kosong
                    if (row.querySelector('td[colspan]')) return;

                    let text = row.innerText.toLowerCase();
                    if (text.includes(keyword)) {
                        row.style.display = '';
                        hasResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Tampilkan pesan jika tidak ada hasil
                let emptyMessage = document.querySelector('#invoiceTable tbody tr td[colspan]');
                if (!hasResults && rows.length > 0 && !emptyMessage) {
                    // Cek apakah sudah ada pesan "Tidak ada hasil"
                    let existingMsg = document.querySelector('#noSearchResult');
                    if (!existingMsg) {
                        let msgRow = document.createElement('tr');
                        msgRow.id = 'noSearchResult';
                        msgRow.innerHTML =
                            '<td colspan="9" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">' +
                            '<i class="bx bx-search-alt text-3xl mb-2 block"></i>' +
                            '<p>Tidak ada tagihan yang cocok dengan pencarian</p></td>';
                        document.querySelector('#invoiceTable tbody').appendChild(msgRow);
                    }
                } else if (hasResults && document.querySelector('#noSearchResult')) {
                    document.querySelector('#noSearchResult').remove();
                }
            });
        </script>
    @endpush
