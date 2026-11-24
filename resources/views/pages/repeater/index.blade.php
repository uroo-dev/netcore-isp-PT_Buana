@extends('layouts.app')
@section('title', 'Repeater')
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-wifi text-gray-600 dark:text-blue-400 text-2xl'></i>
                Repeater
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola data pelanggan wifi per repeater</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <i class='bx bx-check-circle text-lg'></i> {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah Repeater -->
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
        <form method="POST" action="{{ route('repeater.store') }}" class="flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Nama Repeater</label>
                <input type="text" name="nama" placeholder="Contoh: Repeater A" required maxlength="100"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                <i class='bx bx-plus'></i> Tambah
            </button>
        </form>
    </div>

    <!-- Daftar Repeater -->
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 transition-colors duration-200 overflow-hidden">
        @if ($repeaters->count() > 0)
            <div class="divide-y divide-gray-100 dark:divide-dark-700">
                @foreach ($repeaters as $rp)
                    <div class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 dark:hover:bg-dark-700/50 transition group">
                        <a href="{{ route('repeater.show', $rp->id) }}" class="flex items-center gap-4 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                                <i class='bx bx-wifi text-xl'></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition truncate">{{ $rp->nama }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $rp->pelanggan_count }} data tersimpan</p>
                            </div>
                        </a>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                            <a href="{{ route('repeater.show', $rp->id) }}"
                                class="inline-flex items-center gap-1 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-600 dark:text-gray-300 px-3 py-1.5 rounded-xl text-xs font-medium transition">
                                <i class='bx bx-search'></i> Cek
                            </a>
                            <a href="{{ route('repeater.create-bulan', $rp->id) }}"
                                class="inline-flex items-center gap-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 px-3 py-1.5 rounded-xl text-xs font-medium transition">
                                <i class='bx bx-plus'></i> Tambah
                            </a>
                            <form method="POST" action="{{ route('repeater.destroy', $rp->id) }}" onsubmit="return confirm('Hapus repeater {{ $rp->nama }}? Semua data akan hilang.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition p-1">
                                    <i class='bx bx-trash text-lg'></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-dark-700 flex items-center justify-center">
                    <i class='bx bx-wifi-off text-3xl text-gray-400 dark:text-gray-500'></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Belum ada repeater</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambah repeater baru untuk memulai</p>
            </div>
        @endif
    </div>
</div>
@endsection
