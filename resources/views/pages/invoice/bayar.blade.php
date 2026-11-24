@extends('layouts.app')
@section('title', 'Pembayaran ' . $invoice->kode_invoice)
@section('content')
    <div class="p-4 md:p-6">
        <div class="max-w-2xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('invoice.show', $invoice->id) }}"
                    class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-600 dark:hover:text-blue-400 transition-all duration-200 group">
                    <i class='bx bx-arrow-back text-xl group-hover:-translate-x-1 transition-transform'></i>
                    <span>Kembali ke Detail Invoice</span>
                </a>
            </div>

            <!-- Invoice Info Card -->
            <div
                class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden mb-6 transition-colors duration-200">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $invoice->kode_invoice }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm flex items-center gap-1 mt-0.5">
                                <i class='bx bx-user text-xs'></i> {{ $invoice->pelanggan->nama }}
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center gap-1 bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-400 px-4 py-2 rounded-full text-sm font-bold">
                            <i class='bx bx-time text-sm'></i> {{ $invoice->status == 'lunas' ? 'LUNAS' : 'CICILAN' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                        <div class="text-center sm:text-left">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Tagihan</p>
                            <p class="font-bold text-gray-800 dark:text-white text-lg">Rp <span
                                    id="totalTagihanDisplay">{{ number_format($invoice->total, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="text-center sm:text-left">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sudah Dibayar</p>
                            <p class="font-bold text-green-600 dark:text-green-400 text-lg">Rp <span
                                    id="sudahDibayarDisplay">{{ number_format($invoice->dibayar, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="text-center sm:text-left">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sisa Tagihan</p>
                            <p class="font-bold text-red-600 dark:text-red-400 text-2xl">Rp <span
                                    id="sisaTagihanDisplay">{{ number_format($invoice->sisa, 0, ',', '.') }}</span></p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $progressPercent = $invoice->total > 0 ? ($invoice->dibayar / $invoice->total) * 100 : 0;
                    @endphp
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                            <span>Progress Pembayaran</span>
                            <span>{{ round($progressPercent) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-dark-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <form action="{{ route('invoice.prosesBayar', $invoice->id) }}" method="POST" id="paymentForm">
                @csrf
                <div
                    class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <i class='bx bx-money text-green-600 dark:text-green-400 text-xl'></i>
                            Form Pembayaran
                        </h3>

                        <div class="space-y-5">
                            <!-- Nominal Pembayaran -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">
                                    Nominal Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-semibold">Rp</span>
                                    <input type="text" id="nominalInput" value="{{ old('nominal', '') }}"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-dark-700 rounded-xl text-right text-lg font-semibold outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-800 dark:text-white transition-colors"
                                        placeholder="0" autocomplete="off">
                                    <input type="hidden" name="nominal" id="nominalBayar" value="">
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    Maksimal: Rp {{ number_format($invoice->sisa, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="metode" value="cash"
                                            {{ old('metode') == 'cash' ? 'checked' : '' }} required class="peer sr-only">
                                        <div
                                            class="w-full flex items-center justify-center gap-2 p-3 border border-gray-200 dark:border-dark-700 rounded-xl peer-checked:border-gray-500 peer-checked:bg-gray-50 dark:peer-checked:bg-gray-900/30 transition-all duration-200">
                                            <i class='bx bx-money text-xl text-gray-500 peer-checked:text-gray-600'></i>
                                            <span
                                                class="text-gray-700 dark:text-gray-300 peer-checked:text-gray-600">Cash</span>
                                        </div>
                                    </label>
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="metode" value="bank"
                                            {{ old('metode') == 'bank' ? 'checked' : '' }} required class="peer sr-only">
                                        <div
                                            class="w-full flex items-center justify-center gap-2 p-3 border border-gray-200 dark:border-dark-700 rounded-xl peer-checked:border-gray-500 peer-checked:bg-gray-50 dark:peer-checked:bg-gray-900/30 transition-all duration-200">
                                            <i class='bx bx-transfer text-xl text-gray-500 peer-checked:text-gray-600'></i>
                                            <span class="text-gray-700 dark:text-gray-300 peer-checked:text-gray-600">Bank
                                                Transfer</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1.5">
                                    Keterangan <span class="text-gray-400 dark:text-gray-500">(Opsional)</span>
                                </label>
                                <textarea name="keterangan" rows="3"
                                    placeholder="Catatan pembayaran (contoh: transfer via BCA, pembayaran tunai, dll)..."
                                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors resize-none">{{ old('keterangan') }}</textarea>
                            </div>

                            <!-- Preview Pembayaran -->
                            <div id="paymentPreview"
                                class="border-t border-gray-100 dark:border-dark-700 pt-4 mt-2 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Sisa Tagihan</span>
                                    <span class="font-medium text-gray-800 dark:text-white">Rp <span
                                            id="previewSisaAwal">{{ number_format($invoice->sisa, 0, ',', '.') }}</span></span>
                                </div>
                                <div class="flex justify-between text-sm text-green-600 dark:text-green-400">
                                    <span>Nominal Dibayar</span>
                                    <span id="previewNominal" class="font-medium">Rp 0</span>
                                </div>
                                <div class="border-t border-gray-100 dark:border-dark-700 pt-2 mt-1">
                                    <div class="flex justify-between font-bold text-base">
                                        <span class="text-gray-700 dark:text-gray-300">Sisa Setelah Bayar</span>
                                        <span id="previewSisa" class="text-gray-600 dark:text-blue-400 text-lg">Rp
                                            {{ number_format($invoice->sisa, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div id="lunasBadge"
                                    class="hidden mt-3 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 py-2.5 rounded-xl font-semibold text-center flex items-center justify-center gap-2">
                                    <i class='bx bx-check-circle text-lg'></i>
                                    INVOICE AKAN MENJADI LUNAS
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 pt-2">
                            <a href="{{ route('invoice.show', $invoice->id) }}"
                                class="flex-1 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 py-3 rounded-xl font-semibold transition-all duration-200 text-center flex items-center justify-center gap-2">
                                <i class='bx bx-x text-lg'></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                                <i class='bx bx-check-circle text-lg'></i>
                                Konfirmasi Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
    @push('scripts')
        <script>
            const sisaTagihan = {{ $invoice->sisa }};

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function parseRupiah(str) {
                return parseInt(str.replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
            }

            function updatePreview() {
                const nominal = parseRupiah(document.getElementById('nominalInput').value);
                const nominalBayar = Math.min(nominal, sisaTagihan);
                const sisaSetelah = sisaTagihan - nominalBayar;

                document.getElementById('nominalBayar').value = nominalBayar;
                document.getElementById('previewNominal').innerHTML = 'Rp ' + formatRupiah(nominalBayar);

                const previewSisa = document.getElementById('previewSisa');
                const lunasBadge = document.getElementById('lunasBadge');

                if (sisaSetelah <= 0) {
                    previewSisa.innerHTML = 'Rp 0 <span class="text-xs font-normal text-green-600">(LUNAS)</span>';
                    previewSisa.className = 'text-green-600 dark:text-green-400 text-lg';
                    lunasBadge.classList.remove('hidden');
                } else {
                    previewSisa.innerHTML = 'Rp ' + formatRupiah(sisaSetelah);
                    previewSisa.className = 'text-gray-600 dark:text-blue-400 text-lg';
                    lunasBadge.classList.add('hidden');
                }
            }

            document.getElementById('nominalInput').addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                if (value === '') value = '0';
                e.target.value = formatRupiah(parseInt(value));
                updatePreview();
            });

            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                const nominal = parseRupiah(document.getElementById('nominalInput').value);
                if (nominal <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error',
                        text: 'Nominal pembayaran harus lebih dari 0',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                if (nominal > sisaTagihan) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Nominal Melebihi Sisa',
                        text: 'Nominal pembayaran tidak boleh melebihi sisa tagihan Rp ' + formatRupiah(sisaTagihan),
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            });

            updatePreview();
        </script>
    @endpush
