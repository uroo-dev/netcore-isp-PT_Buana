@extends('layouts.app')
@section('title', 'Detail Invoice ' . $invoice->kode_invoice)
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Tombol Navigasi -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <a href="{{ route('invoice.index') }}"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-600 dark:hover:text-blue-400 transition-all duration-200 group">
                <i class='bx bx-arrow-back text-xl group-hover:-translate-x-1 transition-transform'></i>
                <span>Kembali ke Daftar Tagihan</span>
            </a>
            <div class="flex gap-2 w-full sm:w-auto">
                @if ($invoice->status !== 'lunas')
                    <a href="{{ route('invoice.bayar', $invoice->id) }}"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class='bx bx-money text-lg'></i>
                        <span>Bayar Sekarang</span>
                    </a>
                @endif
                <a href="{{ route('invoice.cetak', $invoice->id) }}"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-printer text-lg'></i>
                    <span>Cetak PDF</span>
                </a>
            </div>
        </div>

        @php
            $pengaturan = \App\Models\Pengaturan::first();
        @endphp

        @php
            $tempoWarning = false;
            if ($invoice->jatuh_tempo && $invoice->status !== 'lunas') {
                $daysUntilTempo = now()->diffInDays(\Carbon\Carbon::parse($invoice->jatuh_tempo), false);
                if ($daysUntilTempo <= 3 && $daysUntilTempo >= 0) {
                    $tempoWarning = true;
                }
            }
        @endphp

        <!-- Warning Jatuh Tempo -->
        @if ($tempoWarning)
            <div
                class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center gap-3 transition-all duration-200">
                <i class='bx bx-error-circle text-yellow-600 dark:text-yellow-500 text-2xl'></i>
                <div class="flex-1">
                    <p class="font-semibold text-yellow-800 dark:text-yellow-400">⚠️ Jatuh Tempo Mendekati
                        (H-{{ $daysUntilTempo }})</p>
                    <p class="text-sm text-yellow-700 dark:text-yellow-500">Tagihan ini akan jatuh tempo pada
                        {{ $invoice->jatuh_tempoJ }}. Segera hubungi pelanggan.</p>
                </div>
                @if ($invoice->pelanggan->telepon)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $invoice->pelanggan->telepon) }}" target="_blank"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition shadow-sm">
                        <i class='bx bxl-whatsapp text-lg'></i>
                        WhatsApp
                    </a>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Kartu Invoice -->
                <div
                    class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
                    <div class="p-6">
                        <!-- Header Invoice -->
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                            <div class="flex gap-3">
                                @if ($pengaturan && $pengaturan->logo)
                                    <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo"
                                        class="h-14 w-auto object-contain">
                                @endif
                                <div>
                                    @if ($pengaturan)
                                        <h2 class="font-bold text-lg text-gray-800 dark:text-white">{{ $pengaturan->nama }}
                                        </h2>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pengaturan->alamat }}</p>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if ($invoice->status === 'lunas')
                                    <span
                                        class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-4 py-2 rounded-full text-sm font-bold">
                                        <i class='bx bx-check-circle text-sm'></i> LUNAS
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-400 px-4 py-2 rounded-full text-sm font-bold">
                                        <i class='bx bx-time text-sm'></i> PIUTANG
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                    Dibuat oleh: <span
                                        class="font-medium text-gray-700 dark:text-gray-300">{{ $invoice->user->nama_lengkap }}</span>
                                </p>
                                <p class="text-gray-400 dark:text-gray-500 text-xs">
                                    {{ $invoice->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Info Pelanggan & Tanggal -->
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pelanggan</p>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $invoice->pelanggan->nama }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class='bx bx-phone text-xs'></i> {{ $invoice->pelanggan->telepon ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class='bx bx-map text-xs'></i> {{ $invoice->pelanggan->alamat ?? '-' }}
                                </p>
                            </div>
                            <div class="sm:text-right">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal</p>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $invoice->tanggalJ }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mt-2">Jatuh Tempo
                                </p>
                                <p
                                    class="font-semibold {{ $invoice->jatuh_tempo && $invoice->status !== 'lunas' ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-white' }}">
                                    {{ $invoice->jatuh_tempo ? $invoice->jatuh_tempoJ : '-' }}
                                    @if ($invoice->jatuh_tempo && $invoice->status !== 'lunas' && $daysUntilTempo <= 3 && $daysUntilTempo >= 0)
                                        <span class="text-xs text-red-500 block">({{ $daysUntilTempo }} hari lagi)</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Tabel Item -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead
                                    class="bg-gray-50 dark:bg-dark-700/50 text-gray-700 dark:text-gray-300 border-b dark:border-dark-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Item</th>
                                        <th class="px-4 py-3 text-center w-20">Qty</th>
                                        <th class="px-4 py-3 text-right w-32">Harga</th>
                                        <th class="px-4 py-3 text-right w-32">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y dark:divide-dark-700">
                                    @foreach ($invoice->details as $detail)
                                        <tr>
                                            <td class="px-4 py-3">
                                                @if ($detail->produk_id && $detail->produk)
                                                    <p class="font-medium text-gray-800 dark:text-white">
                                                        {{ $detail->produk->nama }}</p>
                                                @elseif ($detail->produk_id)
                                                    <p class="font-medium text-red-500 italic">Produk tidak ditemukan</p>
                                                @else
                                                    <p class="font-medium text-gray-600 dark:text-gray-400">
                                                        {{ $detail->keterangan ?? 'Lainnya' }}</p>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">
                                                {{ $detail->qty }}</td>
                                            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">
                                                Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-medium text-gray-800 dark:text-white">
                                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Ringkasan Total -->
                        <div class="mt-4 border-t dark:border-dark-700 pt-4 space-y-2">
                            @php
                                $totalItem = $invoice->details->sum('subtotal');
                                $piutangDiInvoice = $invoice->total - ($totalItem - $invoice->diskon + $invoice->pajak);
                            @endphp
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Total Item</span>
                                <span class="text-gray-800 dark:text-white">Rp
                                    {{ number_format($totalItem, 0, ',', '.') }}</span>
                            </div>
                            @if ($invoice->diskon > 0)
                                <div class="flex justify-between text-sm text-red-600 dark:text-red-400">
                                    <span>Diskon</span>
                                    <span>- Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if ($invoice->pajak > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Pajak</span>
                                    <span class="text-gray-800 dark:text-white">+ Rp
                                        {{ number_format($invoice->pajak, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if ($piutangDiInvoice > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Piutang Sebelumnya</span>
                                    <span class="text-orange-600 dark:text-orange-400">+ Rp
                                        {{ number_format($piutangDiInvoice, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between font-bold text-lg border-t dark:border-dark-700 pt-2">
                                <span class="text-gray-800 dark:text-white">Total Tagihan</span>
                                <span class="text-gray-600 dark:text-blue-400">Rp
                                    {{ number_format($invoice->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pembayaran -->
                <div
                    class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                            <i class='bx bx-history text-gray-600 dark:text-blue-400'></i>
                            Riwayat Pembayaran
                        </h3>
                    </div>
                    <div class="p-5">
                        @if ($invoice->pembayaran->count() > 0)
                            <div class="space-y-3">
                                @foreach ($invoice->pembayaran as $bayar)
                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl transition hover:bg-gray-100 dark:hover:bg-dark-700">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="font-medium text-sm text-gray-800 dark:text-white">
                                                    <i class='bx bx-calendar text-xs mr-1'></i>
                                                    {{ $bayar->tanggal->format('d M Y') }}
                                                </p>
                                                <span
                                                    class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2 py-0.5 rounded-full text-xs">
                                                    <i class='bx bx-credit-card text-xs'></i>
                                                    {{ strtoupper($bayar->metode) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Ditangani oleh: <span
                                                    class="font-medium text-gray-700 dark:text-gray-300">{{ $bayar->user->nama_lengkap }}</span>
                                            </p>
                                            @if ($bayar->keterangan)
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                    <i class='bx bx-note text-xs'></i> {{ $bayar->keterangan }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="font-bold text-green-600 dark:text-green-400 text-lg">
                                            + Rp {{ number_format($bayar->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class='bx bx-receipt text-4xl text-gray-400 dark:text-gray-500 mb-2 block'></i>
                                <p class="text-gray-500 dark:text-gray-400">Belum ada pembayaran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan (1/3) - Status Card -->
            <div class="space-y-6">
                <div
                    class="bg-gradient-to-br from-gray-600 to-gray-800 dark:from-blue-900 dark:to-blue-950 rounded-2xl shadow-xl p-6 text-white">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <i class='bx bx-info-circle text-xl'></i>
                        Status Pembayaran
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-200">Total Tagihan</span>
                            <span class="font-semibold">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-200">Sudah Dibayar</span>
                            <span class="font-semibold text-green-300">Rp
                                {{ number_format($invoice->dibayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-500 pt-3 mt-2">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-lg">Sisa</span>
                                <span
                                    class="font-bold text-2xl {{ $invoice->sisa <= 0 ? 'text-green-300' : 'text-yellow-300' }}">
                                    Rp {{ number_format($invoice->sisa, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $progressPercent = $invoice->total > 0 ? ($invoice->dibayar / $invoice->total) * 100 : 0;
                    @endphp
                    <div class="mt-4">
                        <div class="w-full bg-gray-400/30 rounded-full h-2">
                            <div class="bg-green-400 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <p class="text-xs text-gray-200 mt-1 text-right">{{ round($progressPercent) }}% terlunasi</p>
                    </div>

                    @if ($invoice->status !== 'lunas')
                        <a href="{{ route('invoice.bayar', $invoice->id) }}"
                            class="mt-5 w-full bg-white text-gray-600 hover:bg-gray-50 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                            <i class='bx bx-money text-lg'></i>
                            Bayar Sekarang
                        </a>
                    @else
                        <div
                            class="mt-5 w-full bg-green-500/30 backdrop-blur-sm py-3 rounded-xl font-semibold text-center flex items-center justify-center gap-2">
                            <i class='bx bx-check-circle text-lg'></i>
                            Tagihan Sudah Lunas
                        </div>
                    @endif
                </div>

                <!-- Informasi Tambahan (opsional) -->
                @if ($invoice->keterangan)
                    <div
                        class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-5">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class='bx bx-note text-gray-600'></i>
                            Catatan
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->keterangan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
