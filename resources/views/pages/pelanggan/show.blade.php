@extends('layouts.app')
@section('title', 'Detail Pelanggan - ' . $pelanggan->nama)
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Tombol Kembali & Aksi -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <a href="{{ route('pelanggan.index') }}"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-600 dark:hover:text-blue-400 transition-all duration-200 group">
                <i class='bx bx-arrow-back text-xl group-hover:-translate-x-1 transition-transform'></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        <!-- Info Pelanggan - Kartu Utama -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
                    <!-- Avatar dengan inisial -->
                    <div class="relative">
                        <div
                            class="bg-gradient-to-br from-gray-500 to-gray-700 w-20 h-20 rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="text-white text-3xl font-bold">{{ substr($pelanggan->nama, 0, 1) }}</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2">
                            @if ($pelanggan->status == 'on')
                                <span
                                    class="inline-block w-4 h-4 bg-green-500 rounded-full ring-2 ring-white dark:ring-dark-800 animate-pulse"></span>
                            @else
                                <span
                                    class="inline-block w-4 h-4 bg-red-500 rounded-full ring-2 ring-white dark:ring-dark-800"></span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $pelanggan->nama }}</h1>
                            <div class="flex gap-2">
                                <span
                                    class="bg-gray-100 dark:bg-gray-900/40 text-gray-700 dark:text-blue-400 px-2.5 py-1 rounded-lg text-xs font-medium uppercase">
                                    {{ ucfirst($pelanggan->tipe) }}
                                </span>
                                @if ($pelanggan->status == 'on')
                                    <span
                                        class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-lg text-xs font-medium">
                                        <i class='bx bx-check-circle text-xs'></i> Aktif
                                    </span>
                                @else
                                    <span
                                        class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2.5 py-1 rounded-lg text-xs font-medium">
                                        <i class='bx bx-x-circle text-xs'></i> Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Bergabung:
                            {{ $pelanggan->created_at ? \Carbon\Carbon::parse($pelanggan->created_at)->format('d M Y') : '-' }}
                            @if ($pelanggan->status == 'off' && $pelanggan->tanggal_off)
                                • Off sejak: {{ \Carbon\Carbon::parse($pelanggan->tanggal_off)->format('d M Y') }}
                            @endif
                        </p>
                    </div>
                    <!-- Tombol Edit cepat -->
                    <button data-modal-target="editPelangganModal" data-modal-toggle="editPelangganModal"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-dark-600 rounded-xl text-sm transition-all duration-200">
                        <i class='bx bx-edit-alt'></i>
                        <span>Edit</span>
                    </button>
                </div>

                <!-- Grid Info Pelanggan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                        <i class='bx bx-phone text-xl text-gray-500 mt-0.5'></i>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">No. Telepon</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $pelanggan->telepon ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                        <i class='bx bx-map text-xl text-gray-500 mt-0.5'></i>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Alamat</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $pelanggan->alamat ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                        <i class='bx bx-network-chart text-xl text-gray-500 mt-0.5'></i>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">IP Address</p>
                            <p class="font-medium text-gray-800 dark:text-white font-mono text-sm">
                                {{ $pelanggan->ip_address ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                        <i class='bx bx-wifi text-xl text-gray-500 mt-0.5'></i>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Paket WiFi</p>
                            <p class="font-medium text-gray-800 dark:text-white">
                                @if ($pelanggan->tipe == 'mitra')
                                    {{ $pelanggan->paket_wifi_custom ?? '-' }}
                                    @if ($pelanggan->harga_wifi_custom)
                                        <span class="text-xs text-gray-500">(Rp
                                            {{ number_format($pelanggan->harga_wifi_custom, 0, ',', '.') }})</span>
                                    @endif
                                @else
                                    {{ $pelanggan->paket?->nama ?? '-' }}
                                    @if ($pelanggan->paket)
                                        <span class="text-xs text-gray-500">(Rp
                                            {{ number_format($pelanggan->paket->harga, 0, ',', '.') }})</span>
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($pelanggan->tipe != 'mitra')
                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                        <i class='bx bx-user-check text-xl text-gray-500 mt-0.5'></i>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Penanggung Jawab</p>
                            <p class="font-medium text-gray-800 dark:text-white">
                                {{ $pelanggan->penanggungJawab?->nama ?? '-' }}
                                @if ($pelanggan->penanggungJawab?->wilayah)
                                    <span class="text-xs text-gray-500">({{ $pelanggan->penanggungJawab->wilayah }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kartu Ringkasan Statistik -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-blue-900/20 dark:to-gray-800/20 rounded-2xl p-4 border border-gray-200 dark:border-blue-800/30">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-gray-600 dark:text-gray-400 text-xs uppercase tracking-wide">Total Tagihan</p>
                    <i class='bx bx-receipt text-xl text-gray-600 dark:text-blue-400'></i>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalTagihan }}</p>
            </div>
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-4 border border-green-200 dark:border-green-800/30">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-gray-600 dark:text-gray-400 text-xs uppercase tracking-wide">Lunas</p>
                    <i class='bx bx-check-circle text-xl text-green-600 dark:text-green-400'></i>
                </div>
                <p class="text-2xl font-bold text-green-700 dark:text-green-400">{{ $totalLunas }}</p>
            </div>
            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-2xl p-4 border border-orange-200 dark:border-orange-800/30">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-gray-600 dark:text-gray-400 text-xs uppercase tracking-wide">Belum Lunas</p>
                    <i class='bx bx-hourglass text-xl text-orange-600 dark:text-orange-400'></i>
                </div>
                <p class="text-2xl font-bold text-orange-700 dark:text-orange-400">{{ $totalCicilan }}</p>
            </div>
            <div
                class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl p-4 border border-red-200 dark:border-red-800/30">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-gray-600 dark:text-gray-400 text-xs uppercase tracking-wide">Total Piutang</p>
                    <i class='bx bx-money text-xl text-red-600 dark:text-red-400'></i>
                </div>
                <p class="text-xl font-bold text-red-700 dark:text-red-400">Rp
                    {{ number_format($totalPiutang, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Riwayat Tagihan -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
            <div
                class="px-5 py-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-history text-gray-600 dark:text-blue-400'></i>
                    Riwayat Tagihan
                </h3>
                <div class="relative">
                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm'></i>
                    <input type="text" id="searchInvoice" placeholder="Cari tagihan..."
                        class="pl-8 pr-3 py-1.5 text-sm border border-gray-200 dark:border-dark-700 rounded-lg bg-gray-50 dark:bg-dark-700/50 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500/20">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="invoiceTable">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left">No Nota</th>
                            <th class="px-4 py-3 text-center hidden sm:table-cell">Tanggal</th>
                            <th class="px-4 py-3 text-center hidden md:table-cell">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-right hidden md:table-cell">Dibayar</th>
                            <th class="px-4 py-3 text-right">Sisa</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center hidden lg:table-cell">Pembayaran</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-dark-700">
                        @forelse($pelanggan->invoices as $inv)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
                                <td class="px-4 py-3">
                                    <a href="{{ route('invoice.show', $inv->id) }}"
                                        class="text-gray-600 dark:text-blue-400 hover:underline font-mono text-xs font-semibold">
                                        {{ $inv->kode_invoice }}
                                    </a>
                                    <!-- Info mobile: tanggal -->
                                    <div class="sm:hidden text-xs text-gray-500 mt-1">
                                        {{ $inv->tanggalJ }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center hidden sm:table-cell text-gray-600 dark:text-gray-400">
                                    {{ $inv->tanggalJ }}</td>
                                <td class="px-4 py-3 text-center hidden md:table-cell">
                                    @if ($inv->jatuh_tempo && \Carbon\Carbon::parse($inv->jatuh_tempo)->isPast() && $inv->status != 'lunas')
                                        <span
                                            class="text-red-600 dark:text-red-400 font-medium">{{ $inv->jatuh_tempoJ }}</span>
                                    @else
                                        <span
                                            class="text-gray-600 dark:text-gray-400">{{ $inv->jatuh_tempoJ ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-800 dark:text-white">
                                    Rp {{ number_format($inv->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right hidden md:table-cell text-green-600 dark:text-green-400">
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
                                <td class="px-4 py-3 text-center hidden lg:table-cell">
                                    <span
                                        class="bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded-lg text-xs font-medium">
                                        {{ $inv->pembayaran->count() }}x
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('invoice.show', $inv->id) }}"
                                            class="p-1.5 rounded-lg bg-gray-50 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-900/50 transition"
                                            title="Detail">
                                            <i class='bx bx-show text-sm'></i>
                                        </a>
                                        @if ($inv->status != 'lunas')
                                            <a href="{{ route('invoice.bayar', $inv->id) }}"
                                                class="p-1.5 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/50 transition"
                                                title="Bayar">
                                                <i class='bx bx-money text-sm'></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class='bx bx-receipt text-4xl mb-2 block'></i>
                                    <p>Belum ada tagihan untuk pelanggan ini</p>
                                    <a href="{{ route('invoice.create', ['pelanggan_id' => $pelanggan->id]) }}"
                                        class="inline-block mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline">
                                        Buat tagihan pertama →
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
<!-- Modal Edit Pelanggan -->
<div id="editPelangganModal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Pelanggan</h3>
            <button data-modal-hide="editPelangganModal"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
        </div>
        <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="tipe" value="{{ $pelanggan->tipe }}">
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Nama</label>
                <input type="text" name="nama" value="{{ $pelanggan->nama }}"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20">
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">No HP</label>
                <input type="text" name="telepon" value="{{ $pelanggan->telepon }}"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 bg-white dark:bg-dark-800 text-gray-800 dark:text-white">
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Alamat</label>
                <textarea name="alamat" rows="2"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 bg-white dark:bg-dark-800 text-gray-800 dark:text-white">{{ $pelanggan->alamat }}</textarea>
            </div>
            @if ($pelanggan->tipe == 'mitra')
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Paket WiFi</label>
                    <input type="text" name="paket_wifi_custom" value="{{ $pelanggan->paket_wifi_custom }}"
                        placeholder="Ketik paket WiFi..."
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 bg-white dark:bg-dark-800 text-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Harga Paket</label>
                    <input type="text" name="harga_wifi_custom" value="{{ $pelanggan->harga_wifi_custom ? 'Rp '.number_format($pelanggan->harga_wifi_custom, 0, ',', '.') : '' }}"
                        placeholder="Rp 0"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 bg-white dark:bg-dark-800 text-gray-800 dark:text-white"
                        oninput="formatRupiah(this)">
                </div>
            @else
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Paket WiFi</label>
                    <select name="paket_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 bg-white dark:bg-dark-800 text-gray-800 dark:text-white">
                        <option value="">-- Pilih Paket --</option>
                        @foreach ($paketWifi ?? [] as $p)
                            <option value="{{ $p->id }}"
                                {{ $pelanggan->paket_id == $p->id ? 'selected' : '' }}>{{ $p->nama }} - Rp
                                {{ number_format($p->harga, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if($pelanggan->tipe != 'mitra')
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Penanggung Jawab</label>
                <select name="penanggung_jawab_id"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 bg-white dark:bg-dark-800 text-gray-800 dark:text-white">
                    <option value="">-- Pilih Penanggung Jawab --</option>
                    @foreach ($penanggungJawab ?? [] as $pj)
                        <option value="{{ $pj->id }}"
                            {{ $pelanggan->penanggung_jawab_id == $pj->id ? 'selected' : '' }}>{{ $pj->nama }}
                            ({{ $pj->wilayah ?? 'All' }})</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-modal-hide="editPelangganModal"
                    class="px-4 py-2 bg-gray-200 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-xl">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition">Simpan
                    Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        // Search invoice functionality
        document.getElementById('searchInvoice')?.addEventListener('keyup', function() {
            let keyword = this.value.toLowerCase();
            let rows = document.querySelectorAll('#invoiceTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });

        function formatRupiah(input) {
            let val = input.value.replace(/[^0-9]/g, '');
            if (val === '') { input.value = ''; return; }
            let formatted = new Intl.NumberFormat('id-ID').format(val);
            input.value = 'Rp ' + formatted;
        }

        // SweetAlert untuk konfirmasi jika diperlukan
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltip sederhana
            const tooltipElements = document.querySelectorAll('[title]');
            tooltipElements.forEach(el => {
                el.addEventListener('mouseenter', (e) => {
                    // Native title sudah cukup
                });
            });
        });
    </script>
@endpush
