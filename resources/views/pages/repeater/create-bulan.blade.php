@extends('layouts.app')
@section('title', 'Tambah Data - ' . $r->nama)
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-plus-circle text-gray-600 dark:text-blue-400 text-2xl'></i>
                Tambah Data &mdash; {{ $r->nama }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Input pelanggan wifi per repeater</p>
        </div>
        <a href="{{ route('repeater.show', $r->id) }}"
            class="inline-flex items-center gap-2 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium transition">
            <i class='bx bx-arrow-back'></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
        <form method="GET" action="{{ route('repeater.create-bulan', $r->id) }}" class="flex items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Pilih Bulan</label>
                <input type="month" name="bulan" value="{{ request('bulan', now()->format('Y-m')) }}"
                    class="border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                <i class='bx bx-search'></i> Pilih
            </button>
        </form>
    </div>

    @php
        $bulan = request('bulan', now()->format('Y-m'));
        $carbon = \Carbon\Carbon::parse($bulan . '-01');
        $bulanNum = $carbon->month;
        $tahun = $carbon->year;
    @endphp

    <form method="POST" action="{{ route('repeater.store-bulan', $r->id) }}" id="formPelanggan">
        @csrf
        <input type="hidden" name="bulan" value="{{ $bulanNum }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">

        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 transition-colors duration-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 dark:text-white">{{ $carbon->translatedFormat('F Y') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-dark-700/50 border-b border-gray-100 dark:border-dark-700">
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 w-8">No</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Nama Pelanggan</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Paket</th>
                            <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Harga</th>
                            <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 w-16">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPelanggan" class="divide-y divide-gray-100 dark:divide-dark-700">
                        <!-- rows akan diisi JS -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="px-4 py-4">
                                <button type="button" onclick="tambahBaris()"
                                    class="w-full flex items-center justify-center gap-2 bg-gray-50 dark:bg-dark-700/50 border-2 border-dashed border-gray-300 dark:border-dark-600 text-gray-500 dark:text-gray-400 hover:border-blue-400 hover:text-blue-600 dark:hover:border-blue-500 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/10 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200">
                                    <i class='bx bx-plus text-lg'></i> Tambah Baris
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <a href="{{ route('repeater.show', $r->id) }}"
                class="px-5 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 transition">Batal</a>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                <i class='bx bx-save'></i> Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let rowCount = 0;

    function tambahBaris() {
        const idx = rowCount++;
        const tbody = document.getElementById('tbodyPelanggan');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 dark:hover:bg-dark-700/30 transition';
        tr.innerHTML = `
            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-center">${idx + 1}</td>
            <td class="px-4 py-3">
                <input type="text" name="pelanggan[${idx}][nama]" required maxlength="100" placeholder="Nama pelanggan"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="pelanggan[${idx}][paket]" maxlength="100" placeholder="Nama paket"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
            </td>
            <td class="px-4 py-3">
                <input type="number" name="pelanggan[${idx}][harga]" required min="0" step="0.01" placeholder="0"
                    class="w-40 text-right border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" onclick="hapusBaris(this)"
                    class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition p-1">
                    <i class='bx bx-trash text-lg'></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function hapusBaris(btn) {
        btn.closest('tr').remove();
    }

    // mulai dengan 1 baris
    tambahBaris();
</script>
@endpush
