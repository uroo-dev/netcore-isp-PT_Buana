@extends('layouts.app')
@section('title', 'Slip Gaji')
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-receipt text-gray-600 dark:text-blue-400 text-2xl'></i>
                Slip Gaji
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola penggajian karyawan per bulan</p>
        </div>
        <a href="{{ route('slip-gaji.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
            <i class='bx bx-plus'></i> Buat Slip Gaji
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <i class='bx bx-check-circle text-lg'></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 transition-colors duration-200 overflow-hidden">
        @if ($slipGaji->count() > 0)
            <div class="divide-y divide-gray-100 dark:divide-dark-700">
                @foreach ($slipGaji as $slip)
                    <a href="{{ route('slip-gaji.show', $slip->id) }}"
                        class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 dark:hover:bg-dark-700/50 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <i class='bx bx-receipt text-xl'></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $slip->nama }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $slip->details_count }} karyawan &middot; Total Rp{{ number_format($slip->total_gaji, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $slip->created_at->format('d/m/Y') }}</span>
                            <i class='bx bx-chevron-right text-gray-400 group-hover:text-blue-500 transition text-lg'></i>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700">
                {{ $slipGaji->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-dark-700 flex items-center justify-center">
                    <i class='bx bx-receipt text-3xl text-gray-400 dark:text-gray-500'></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Belum ada slip gaji</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat slip gaji baru untuk memulai</p>
                <a href="{{ route('slip-gaji.create') }}"
                    class="inline-flex items-center gap-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class='bx bx-plus'></i> Buat Slip Gaji
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
