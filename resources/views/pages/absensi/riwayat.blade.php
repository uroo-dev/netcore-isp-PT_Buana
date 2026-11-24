@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bxs-calendar-check text-gray-600 dark:text-blue-400'></i>
                Riwayat Absensi
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Klik tanggal untuk melihat detail absensi</p>
        </div>
        @if($detail->isNotEmpty())
        <a href="{{ route('absensi.cetak', ['dari' => $tanggal, 'sampai' => $tanggal]) }}"
            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md"
            target="_blank">
            <i class='bx bx-printer text-lg'></i>
            Cetak
        </a>
        @endif
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('absensi.riwayat', ['bulan' => $carbon->copy()->subMonth()->format('Y-m'), 'tanggal' => $tanggal]) }}"
                class="inline-flex items-center gap-1 px-3 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                <i class='bx bx-chevron-left'></i>
            </a>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $namaBulan }}</h3>
            <a href="{{ route('absensi.riwayat', ['bulan' => $carbon->copy()->addMonth()->format('Y-m'), 'tanggal' => $tanggal]) }}"
                class="inline-flex items-center gap-1 px-3 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                <i class='bx bx-chevron-right'></i>
            </a>
        </div>

        <div class="grid grid-cols-7 gap-2">
            @php $hari = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']; @endphp
            @foreach($hari as $h)
                <div class="text-center text-xs font-semibold text-gray-500 dark:text-gray-400 py-2">{{ $h }}</div>
            @endforeach

            @for($i = 0; $i < $mulaiHari; $i++)
                <div></div>
            @endfor

            @foreach($daftarTanggal as $tgl)
                @php
                    $isSelected = $tgl['date'] === $tanggal;
                    $boxClass = 'border border-gray-200 dark:border-dark-700 rounded-xl p-2 text-center cursor-pointer transition-all duration-200 hover:shadow-md ';

                    if ($tgl['hasAbsensi']) {
                        $boxClass .= 'bg-green-100 dark:bg-green-900/30 border-green-300 dark:border-green-700 ';
                    } elseif ($tgl['isPast']) {
                        $boxClass .= 'bg-gray-50 dark:bg-dark-800/50 ';
                    } else {
                        $boxClass .= 'bg-white dark:bg-dark-800 ';
                    }

                    if ($isSelected) {
                        $boxClass .= 'ring-2 ring-gray-600 dark:ring-blue-400 shadow-md ';
                    }

                    if ($tgl['isToday']) {
                        $boxClass .= 'font-bold ';
                    }
                @endphp
                <a href="{{ route('absensi.riwayat', ['tanggal' => $tgl['date'], 'bulan' => $bulan]) }}"
                    class="{{ $boxClass }}">
                    <div class="text-sm {{ $tgl['isToday'] ? 'text-gray-800 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
                        {{ $tgl['day'] }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Detail Absensi -->
    @if($tanggal)
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-calendar text-gray-600 dark:text-blue-400'></i>
                Detail Absensi {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </h3>
            @if($detail->isNotEmpty())
            <span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full font-medium">
                {{ $detail->count() }} tercatat
            </span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                    <tr>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Nama Karyawan</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Jam Masuk</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Jam Pulang</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Keterangan</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
                    @forelse($detail as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-800 dark:text-white">{{ $item->karyawan?->nama_lengkap ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $statusClasses = [
                                        'datang' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800/30',
                                        'terlambat' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800/30',
                                        'absen' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800/30',
                                        'libur' => 'bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400 border-gray-200 dark:border-gray-800/30',
                                    ];
                                    $statusLabels = [
                                        'datang' => 'Datang',
                                        'terlambat' => 'Terlambat',
                                        'absen' => 'Absen',
                                        'libur' => 'Libur',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClasses[$item->status] ?? $statusClasses['absen'] }}">
                                    {{ $statusLabels[$item->status] ?? ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $item->jam_masuk ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $item->jam_pulang ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $item->keterangan ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $item->creator?->nama_lengkap ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <i class='bx bx-calendar-x text-5xl text-gray-300 dark:text-gray-600'></i>
                                <p class="text-gray-400 dark:text-gray-500 mt-3">Belum ada data absensi untuk tanggal ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
