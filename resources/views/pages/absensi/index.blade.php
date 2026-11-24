@extends('layouts.app')

@section('title', 'Absensi Harian')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bxs-calendar-check text-gray-600 dark:text-blue-400'></i>
                Absensi Harian
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Klik tanggal untuk mencatat absensi karyawan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('absensi.index', ['bulan' => $carbon->copy()->subMonth()->format('Y-m')]) }}"
                class="inline-flex items-center gap-1 px-3 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                <i class='bx bx-chevron-left'></i>
            </a>
            <select onchange="window.location.href='{{ route('absensi.index') }}?bulan='+this.value"
                class="border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                @for($m = 1; $m <= 12; $m++)
                    @php $val = now()->format('Y-') . str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $val }}" {{ $bulan == $val ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
            <a href="{{ route('absensi.index', ['bulan' => $carbon->copy()->addMonth()->format('Y-m')]) }}"
                class="inline-flex items-center gap-1 px-3 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                <i class='bx bx-chevron-right'></i>
            </a>
            <form action="{{ route('absensi.rekap-pdf') }}" method="GET" target="_blank" class="flex gap-2 items-center">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <select name="karyawan_id"
                    class="border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                    <option value="">Semua Karyawan</option>
                    @foreach ($karyawanList as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-file'></i> Export PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-5">
        <div class="text-center mb-4">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $namaBulan }}</h3>
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
                    $boxClass = 'border border-gray-200 dark:border-dark-700 rounded-xl p-2 text-center cursor-pointer transition-all duration-200 hover:shadow-md ';

                    if ($tgl['hasAbsensi']) {
                        $boxClass .= 'bg-green-100 dark:bg-green-900/30 border-green-300 dark:border-green-700 ';
                    } elseif ($tgl['isPast']) {
                        $boxClass .= 'bg-gray-50 dark:bg-dark-800/50 ';
                    } else {
                        $boxClass .= 'bg-white dark:bg-dark-800 ';
                    }

                    if ($tgl['isToday']) {
                        $boxClass .= 'font-bold ';
                    }

                    $dateName = \Carbon\Carbon::parse($tgl['date'])->translatedFormat('l');
                @endphp
                <a href="{{ route('absensi.form', ['tanggal' => $tgl['date']]) }}"
                    class="{{ $boxClass }}">
                    <div class="text-sm {{ $tgl['isToday'] ? 'text-gray-800 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
                        {{ $tgl['day'] }}
                    </div>
                    <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ $dateName }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
