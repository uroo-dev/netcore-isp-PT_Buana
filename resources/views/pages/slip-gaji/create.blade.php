@extends('layouts.app')
@section('title', 'Buat Slip Gaji')
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-plus-circle text-gray-600 dark:text-blue-400 text-2xl'></i>
                Buat Slip Gaji
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pilih bulan dan atur gaji karyawan</p>
        </div>
        <a href="{{ route('slip-gaji.index') }}"
            class="inline-flex items-center gap-2 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium transition">
            <i class='bx bx-arrow-back'></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
        <form method="GET" action="{{ route('slip-gaji.create') }}" class="flex items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Pilih Bulan</label>
                <input type="month" name="bulan" value="{{ $bulan }}"
                    class="border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                <i class='bx bx-search'></i> Tampilkan
            </button>
        </form>
    </div>

    @if (count($data) > 0)
        <form method="POST" action="{{ route('slip-gaji.store') }}" id="formSlipGaji">
            @csrf
            <input type="hidden" name="bulan" value="{{ $bulanNum }}">
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 transition-colors duration-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-dark-700/50 border-b border-gray-100 dark:border-dark-700">
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Karyawan</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Telepon</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Hadir</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Terlambat</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Absen</th>
                                <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Gaji Pokok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
                            @foreach ($data as $idx => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                                    <td class="px-4 py-3">
                                        <input type="hidden" name="karyawan_id[{{ $idx }}]" value="{{ $item['karyawan']->id }}">
                                        <span class="font-medium text-gray-800 dark:text-white">{{ $item['karyawan']->nama_lengkap }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $item['karyawan']->telepon ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="number" name="total_hadir[{{ $idx }}]" value="{{ old('total_hadir.' . $idx, $item['total_hadir']) }}"
                                            class="w-16 text-center border border-green-200 dark:border-green-800 rounded-xl px-2 py-1.5 text-xs focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="number" name="total_terlambat[{{ $idx }}]" value="{{ old('total_terlambat.' . $idx, $item['total_terlambat']) }}"
                                            class="w-16 text-center border border-yellow-200 dark:border-yellow-800 rounded-xl px-2 py-1.5 text-xs focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="number" name="total_absen[{{ $idx }}]" value="{{ old('total_absen.' . $idx, $item['total_absen']) }}"
                                            class="w-16 text-center border border-red-200 dark:border-red-800 rounded-xl px-2 py-1.5 text-xs focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <input type="number" name="gaji_pokok[{{ $idx }}]" value="{{ old('gaji_pokok.' . $idx, $item['karyawan']->gaji_pokok ?? 0) }}"
                                            class="gaji-input w-36 text-right border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                                            data-idx="{{ $idx }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total & Submit -->
            <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Gaji</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white" id="totalGaji">Rp 0</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('slip-gaji.index') }}"
                            class="px-5 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                            <i class='bx bx-save'></i> Simpan Slip Gaji
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-10 text-center transition-colors duration-200">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-dark-700 flex items-center justify-center">
                <i class='bx bx-calendar-x text-3xl text-gray-400 dark:text-gray-500'></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Tidak ada data absensi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Belum ada absensi untuk bulan ini</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.gaji-input').forEach(el => {
            total += parseFloat(el.value) || 0;
        });
        document.getElementById('totalGaji').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.querySelectorAll('.gaji-input').forEach(el => {
        el.addEventListener('input', hitungTotal);
    });

    hitungTotal();
</script>
@endpush
