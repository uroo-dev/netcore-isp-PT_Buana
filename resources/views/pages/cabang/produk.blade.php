@extends('layouts.app')
@section('title', 'Produk Cabang - ' . $cabang->nama)
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('cabang.index') }}"
                class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm mb-2 transition">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bxs-buildings text-gray-600 dark:text-blue-400'></i>
                Produk {{ $cabang->nama }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Daftar persediaan barang di cabang ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-900/30 rounded-xl flex items-center justify-center">
                    <i class='bx bx-package text-gray-600 dark:text-blue-400 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalProduk }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-900/30 rounded-xl flex items-center justify-center">
                    <i class='bx bx-cube text-gray-600 dark:text-blue-400 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Total Stok</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalStok }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-900/30 rounded-xl flex items-center justify-center">
                    <i class='bx bx-check-circle text-gray-600 dark:text-blue-400 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Produk Tersedia</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $produkCabang->where('stok', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">#</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Produk</th>
                        <th class="px-4 py-3 text-center font-semibold">Stok</th>
                        <th class="px-4 py-3 text-center font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-dark-700">
                    @forelse($produkCabang as $item)
                    @php
                        $status = $item->stok > 10 ? 'tersedia' : ($item->stok > 0 ? 'menipis' : 'habis');
                        $statusClass = $status === 'tersedia' ? 'text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-400' : ($status === 'menipis' ? 'text-amber-600 bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400' : 'text-red-600 bg-red-100 dark:bg-red-900/30 dark:text-red-400');
                        $statusLabel = $status === 'tersedia' ? 'Tersedia' : ($status === 'menipis' ? 'Menipis' : 'Habis');
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">{{ $item->produk->nama }}</td>
                        <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ $item->stok }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                            <i class='bx bx-package text-4xl mb-2 block'></i>
                            <p>Belum ada produk di cabang ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
