@extends('layouts.app')
@section('title', $slipGaji->nama)
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-receipt text-gray-600 dark:text-blue-400 text-2xl'></i>
                {{ $slipGaji->nama }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Detail penggajian karyawan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('slip-gaji.cetak-pdf', $slipGaji->id) }}" target="_blank"
                class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                <i class='bx bx-file'></i> Cetak PDF
            </a>
            <a href="{{ route('slip-gaji.index') }}"
                class="inline-flex items-center gap-2 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium transition">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <i class='bx bx-check-circle text-lg'></i> {{ session('success') }}
        </div>
    @endif

    <!-- Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Karyawan</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $slipGaji->details->count() }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bulan</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ Carbon\Carbon::create($slipGaji->tahun, $slipGaji->bulan, 1)->translatedFormat('F Y') }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Gaji</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">Rp {{ number_format($slipGaji->total_gaji, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $slipGaji->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Daftar Karyawan -->
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 transition-colors duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">Daftar Gaji Karyawan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-700/50 border-b border-gray-100 dark:border-dark-700">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">No</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Karyawan</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Hadir</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Telat</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Absen</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Gaji Pokok</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Total</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
                    @foreach ($slipGaji->details as $detail)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-800 dark:text-white">{{ $detail->karyawan->nama_lengkap }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-2.5 py-0.5 rounded-full text-xs font-medium">{{ $detail->total_hadir }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 px-2.5 py-0.5 rounded-full text-xs font-medium">{{ $detail->total_terlambat }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-2.5 py-0.5 rounded-full text-xs font-medium">{{ $detail->total_absen }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-800 dark:text-white">
                                Rp {{ number_format($detail->gaji_pokok, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                                Rp {{ number_format($detail->total_gaji, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="openEditModal({{ $detail->id }}, '{{ $detail->karyawan->nama_lengkap }}', {{ $detail->gaji_pokok }}, {{ $detail->total_hadir }}, {{ $detail->total_terlambat }}, {{ $detail->total_absen }})"
                                    class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-xs font-medium transition">
                                    <i class='bx bx-edit'></i> Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeEditModal()">
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl border border-gray-100 dark:border-dark-700 w-full max-w-md p-6 transition-colors duration-200" onclick="event.stopPropagation()">
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white">Edit Gaji</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4" id="editKaryawanName"></p>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Gaji Pokok</label>
                    <input type="number" name="gaji_pokok" id="editGajiPokok" step="0.01" min="0" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Hadir</label>
                        <input type="number" name="total_hadir" id="editTotalHadir" min="0" required
                            class="w-full border border-green-200 dark:border-green-800 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Terlambat</label>
                        <input type="number" name="total_terlambat" id="editTotalTerlambat" min="0" required
                            class="w-full border border-yellow-200 dark:border-yellow-800 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Absen</label>
                        <input type="number" name="total_absen" id="editTotalAbsen" min="0" required
                            class="w-full border border-red-200 dark:border-red-800 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                    <i class='bx bx-save'></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditModal(id, nama, gajiPokok, totalHadir, totalTerlambat, totalAbsen) {
        document.getElementById('editForm').action = '{{ route("slip-gaji.update-detail", ":id") }}'.replace(':id', id);
        document.getElementById('editKaryawanName').textContent = nama;
        document.getElementById('editGajiPokok').value = gajiPokok;
        document.getElementById('editTotalHadir').value = totalHadir;
        document.getElementById('editTotalTerlambat').value = totalTerlambat;
        document.getElementById('editTotalAbsen').value = totalAbsen;
        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endpush
