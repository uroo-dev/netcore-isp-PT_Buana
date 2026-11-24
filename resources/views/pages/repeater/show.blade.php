@extends('layouts.app')
@section('title', $r->nama)
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-wifi text-gray-600 dark:text-blue-400 text-2xl'></i>
                {{ $r->nama }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pilih bulan untuk lihat detail</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('repeater.create-bulan', $r->id) }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                <i class='bx bx-plus'></i> Tambah Bulan
            </a>
            <a href="{{ route('repeater.index') }}"
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

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 transition-colors duration-200 overflow-hidden">
        @if ($bulanList->count() > 0)
            <div class="divide-y divide-gray-100 dark:divide-dark-700">
                @foreach ($bulanList as $b)
                    <a href="{{ route('repeater.show-bulan', [$r->id, $b->bulan, $b->tahun]) }}"
                        class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 dark:hover:bg-dark-700/50 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                <i class='bx bx-calendar text-xl'></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                    {{ Carbon\Carbon::create($b->tahun, $b->bulan, 1)->translatedFormat('F Y') }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $b->total_pelanggan }} pelanggan &middot;
                                    Rp{{ number_format($b->total_pendapatan, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">PPN: Rp{{ number_format($b->total_ppn, 0, ',', '.') }}</span>
                            <i class='bx bx-chevron-right text-gray-400 group-hover:text-blue-500 transition text-lg'></i>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-dark-700 flex items-center justify-center">
                    <i class='bx bx-calendar-x text-3xl text-gray-400 dark:text-gray-500'></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Belum ada data</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Klik "Tambah Bulan" untuk mulai mengisi</p>
            </div>
        @endif
    </div>
</div>
@endsection
