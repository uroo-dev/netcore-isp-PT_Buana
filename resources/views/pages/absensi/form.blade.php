@extends('layouts.app')

@section('title', 'Form Absensi')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bxs-calendar-check text-gray-600 dark:text-blue-400'></i>
                Form Absensi
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <a href="{{ route('absensi.index', ['bulan' => \Carbon\Carbon::parse($tanggal)->format('Y-m')]) }}"
            class="inline-flex items-center gap-1 px-3 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
            <i class='bx bx-arrow-back'></i> Kembali ke Kalender
        </a>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-calendar text-gray-600 dark:text-blue-400'></i>
                Absensi {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </h3>
            @if($absensiHariIni->isNotEmpty())
            <span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full font-medium">
                {{ $absensiHariIni->count() }} tercatat
            </span>
            @endif
        </div>

        <form action="{{ route('absensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">
                                Nama Lengkap
                            </th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">
                                Status
                            </th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">
                                Jam Masuk
                            </th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">
                                Jam Pulang
                            </th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">
                                Keterangan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
                        @forelse($karyawan as $k)
                            @php $absensi = $absensiHariIni->get($k->id); @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150 {{ $absensi ? 'bg-green-50/50 dark:bg-green-900/5' : '' }}">
                                <td class="px-5 py-3">
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $k->nama_lengkap }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <select name="absensi[{{ $k->id }}][status]"
                                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                        <option value="">-- Pilih --</option>
                                        <option value="datang" {{ $absensi && $absensi->status === 'datang' ? 'selected' : '' }}>Datang</option>
                                        <option value="terlambat" {{ $absensi && $absensi->status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                        <option value="absen" {{ $absensi && $absensi->status === 'absen' ? 'selected' : '' }}>Absen</option>
                                        <option value="libur" {{ $absensi && $absensi->status === 'libur' ? 'selected' : '' }}>Libur</option>
                                    </select>
                                </td>
                                <td class="px-5 py-3">
                                    <input type="time" name="absensi[{{ $k->id }}][jam_masuk]"
                                        value="{{ $absensi?->jam_masuk ?? '' }}"
                                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                </td>
                                <td class="px-5 py-3">
                                    <input type="time" name="absensi[{{ $k->id }}][jam_pulang]"
                                        value="{{ $absensi?->jam_pulang ?? '' }}"
                                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                </td>
                                <td class="px-5 py-3">
                                    <input type="text" name="absensi[{{ $k->id }}][keterangan]"
                                        value="{{ $absensi?->keterangan ?? '' }}"
                                        placeholder="Opsional"
                                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <i class='bx bx-user-x text-5xl text-gray-300 dark:text-gray-600'></i>
                                    <p class="text-gray-400 dark:text-gray-500 mt-3">Belum ada karyawan</p>
                                    <a href="{{ route('pegawai.index') }}" class="mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline inline-block">
                                        Tambah karyawan dulu →
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($karyawan->isNotEmpty())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700 flex items-center justify-between">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Status kosong tidak akan disimpan
                </p>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-save text-lg'></i>
                    Simpan Absensi
                </button>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
