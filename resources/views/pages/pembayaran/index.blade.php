@extends('layouts.app')
@section('title', 'Riwayat Pembayaran')
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-history text-gray-600 dark:text-blue-400 text-2xl'></i>
                    Riwayat Pembayaran
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Transaksi pembayaran per invoice</p>
            </div>

            <!-- Info Ringkas -->
            <div class="flex gap-3">
                <div class="bg-green-100 dark:bg-green-900/30 rounded-xl px-3 py-1.5 text-center">
                    <p class="text-xs text-green-600 dark:text-green-400">Total Pemasukan</p>
                    <p class="font-bold text-green-700 dark:text-green-300 text-sm">Rp
                        {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-gray-900/30 rounded-xl px-3 py-1.5 text-center">
                    <p class="text-xs text-gray-600 dark:text-blue-400">Total Transaksi</p>
                    <p class="font-bold text-gray-700 dark:text-blue-300 text-sm">{{ $invoices->total() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <form method="GET" action="{{ route('pembayaran.index') }}" class="flex flex-wrap items-end gap-4">
                <!-- Tanggal Awal -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Tanggal Awal
                    </label>
                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>

                <!-- Tanggal Akhir -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-calendar text-xs mr-1'></i> Tanggal Akhir
                    </label>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>

                <!-- No Invoice -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-receipt text-xs mr-1'></i> No Invoice
                    </label>
                    <div class="relative">
                        <i
                            class='bx bx-receipt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm'></i>
                        <input type="text" name="no_nota" value="{{ request('no_nota') }}" placeholder="INV-..."
                            class="w-full pl-9 pr-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>

                <!-- Nama Pelanggan -->
                <div class="flex-[2] min-w-[180px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        <i class='bx bx-user text-xs mr-1'></i> Nama Pelanggan
                    </label>
                    <div class="relative">
                        <i
                            class='bx bx-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm'></i>
                        <input type="text" name="nama_pelanggan" value="{{ request('nama_pelanggan') }}"
                            placeholder="Cari pelanggan..."
                            class="w-full pl-9 pr-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('pembayaran.index') }}"
                        class="flex items-center gap-1.5 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </form>

            {{-- Info hasil filter --}}
            @if (request('tanggal_awal') || request('tanggal_akhir') || request('no_nota') || request('nama_pelanggan'))
                <div
                    class="mt-4 pt-3 border-t border-gray-100 dark:border-dark-700 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class='bx bx-info-circle'></i>
                    <span>Filter aktif:</span>
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
                    @if (request('no_nota'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                            Invoice: {{ request('no_nota') }}
                        </span>
                    @endif
                    @if (request('nama_pelanggan'))
                        <span
                            class="bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full">
                            Pelanggan: "{{ request('nama_pelanggan') }}"
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Tabel Invoice dengan Pembayaran -->
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
                            <th class="px-4 py-3 text-right font-semibold">Total Bayar</th>
                            <th class="px-4 py-3 text-center font-semibold hidden md:table-cell">Jml Bayar</th>
                            <th class="px-4 py-3 text-center font-semibold">Status</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
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
                                    <!-- Info mobile: tanggal pembayaran terakhir -->
                                    <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $inv->pembayaran->last()?->tanggal->format('d M Y') ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $inv->pelanggan->nama }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">
                                        {{ $inv->pembayaran->count() }}x pembayaran
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center hidden sm:table-cell text-gray-600 dark:text-gray-400">
                                    {{ $inv->pembayaran->last()?->tanggal->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                                    Rp {{ number_format($inv->dibayar, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center hidden md:table-cell">
                                    <span
                                        class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2.5 py-1 rounded-full text-xs font-medium">
                                        <i class='bx bx-money text-xs'></i>
                                        {{ $inv->pembayaran->count() }}x
                                    </span>
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
                                    <button onclick="showDetailPembayaran({{ $inv->id }})"
                                        class="inline-flex items-center gap-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200">
                                        <i class='bx bx-detail text-sm'></i>
                                        <span class="hidden sm:inline">Detail</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class='bx bx-receipt text-4xl mb-2 block'></i>
                                    <p>Tidak ada pembayaran</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($invoices->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-dark-700">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Detail Pembayaran -->
    <div id="modalDetail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-2xl p-6 max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 sticky top-0 bg-white dark:bg-dark-800 pb-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-receipt text-gray-600'></i> Detail Pembayaran
                </h3>
                <button onclick="hideDetail()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <div id="detailContent"></div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function showDetailPembayaran(invoiceId) {
            fetch(`/pembayaran/${invoiceId}/detail`)
                .then(res => res.json())
                .then(data => {
                    const inv = data.invoice;
                    let html = `
                <div class="mb-5 p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-blue-900/30 dark:to-gray-800/20 rounded-xl border border-gray-200 dark:border-blue-800/30">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                        <div>
                            <p class="font-bold text-gray-800 dark:text-white text-lg">${inv.kode_invoice}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                <i class='bx bx-user text-xs'></i> ${inv.pelanggan.nama}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                            ${inv.status === 'lunas'
                                ? '<span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2 py-0.5 rounded-full text-xs"><i class="bx bx-check-circle"></i> LUNAS</span>'
                                : '<span class="inline-flex items-center gap-1 bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-400 px-2 py-0.5 rounded-full text-xs"><i class="bx bx-time"></i> CICILAN</span>'
                            }
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mt-3 pt-3 border-t border-gray-200 dark:border-blue-800/30">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Tagihan</p>
                            <p class="font-semibold text-gray-800 dark:text-white">Rp ${formatNumber(inv.total)}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sudah Dibayar</p>
                            <p class="font-semibold text-green-600 dark:text-green-400">Rp ${formatNumber(inv.dibayar)}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sisa</p>
                            <p class="font-semibold ${inv.sisa > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600'}">Rp ${formatNumber(inv.sisa)}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-dark-700/50 rounded-t-lg">
                            <tr class="text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                                <th class="px-3 py-2 text-left">Tanggal</th>
                                <th class="px-3 py-2 text-left">Admin</th>
                                <th class="px-3 py-2 text-left">Metode</th>
                                <th class="px-3 py-2 text-left hidden md:table-cell">Keterangan</th>
                                <th class="px-3 py-2 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-dark-700">
            `;

                    data.pembayaran.forEach(p => {
                        html += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                        <td class="px-3 py-2 text-gray-700 dark:text-gray-300">${p.tanggal}</td>
                        <td class="px-3 py-2 text-gray-700 dark:text-gray-300">${p.admin}</td>
                        <td class="px-3 py-2">
                            <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full text-xs">
                                <i class='bx ${p.metode === 'cash' ? 'bx-money' : 'bx-transfer'} text-xs'></i>
                                ${p.metode.toUpperCase()}
                            </span>
                        </td>
                        <td class="px-3 py-2 hidden md:table-cell text-gray-500 dark:text-gray-400 text-xs max-w-[150px] truncate">${p.keterangan || '-'}</td>
                        <td class="px-3 py-2 text-right font-semibold text-green-600 dark:text-green-400">Rp ${formatNumber(p.nominal)}</td>
                    </tr>
                `;
                    });

                    html += `
                        </tbody>
                    </table>
                </div>
                ${data.pembayaran.length === 0 ? '<p class="text-center text-gray-500 py-4">Belum ada pembayaran</p>' : ''}
            `;

                    document.getElementById('detailContent').innerHTML = html;
                    document.getElementById('modalDetail').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Gagal memuat detail pembayaran', 'error');
                });
        }

        function hideDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        // Tutup modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('modalDetail');
                if (modal && !modal.classList.contains('hidden')) {
                    hideDetail();
                }
            }
        });
    </script>
@endpush
