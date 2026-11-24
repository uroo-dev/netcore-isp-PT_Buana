@extends('layouts.app')
@section('title', 'Dashboard Pajak')
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-calculator text-amber-600 text-2xl'></i>
                Dashboard Pajak
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Proyeksi DPP, PPN, BHP USO per bulan</p>
        </div>
        <form method="GET" action="{{ route('dashboard.pajak') }}" id="pajakFilterForm" class="flex items-center gap-2">
            <input type="month" name="bulan" value="{{ $bulan }}"
                class="border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
            <button type="submit"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl text-sm transition">Tampilkan</button>
        </form>
    </div>

    <div id="pajakContent">
        @include('pages.dashboard.partials.pajak-content')
    </div>
</div>
@endsection

@push('scripts')
<script>
    // AJAX Filter bulan Dashboard Pajak
    const pajakForm = document.getElementById('pajakFilterForm');
    const pajakContent = document.getElementById('pajakContent');

    async function fetchPajak(page = null) {
        const params = new URLSearchParams(new FormData(pajakForm));
        if (page) params.set('page', page);

        try {
            const res = await fetch('{{ route('dashboard.pajak.data') }}' + '?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            pajakContent.innerHTML = data.content;
            bindPajakPagination();
        } catch (e) {
            window.location.href = '{{ route('dashboard.pajak') }}' + '?' + params.toString();
        }
    }

    function bindPajakPagination() {
        const wrap = document.getElementById('pajakPagination');
        if (!wrap) return;
        wrap.querySelectorAll('a[href]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(link.href);
                fetchPajak(url.searchParams.get('page'));
            });
        });
    }

    pajakForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchPajak(1);
    });

    bindPajakPagination();
</script>
@endpush
