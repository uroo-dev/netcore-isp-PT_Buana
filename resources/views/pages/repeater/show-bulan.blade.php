@extends('layouts.app')
@section('title', $r->nama . ' - ' . \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y'))
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-wifi text-gray-600 dark:text-blue-400 text-2xl'></i>
                {{ $r->nama }} &mdash; {{ \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y') }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Detail pelanggan wifi per repeater</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('repeater.show', $r->id) }}"
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

    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total_pelanggan'] }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pendapatan</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">Rp{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">PPN</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">Rp{{ number_format($stats['total_ppn'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">BHP USO</p>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">Rp{{ number_format($stats['total_bhp_uso'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Tabel -->
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 transition-colors duration-200 overflow-hidden">
        @if ($data->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-dark-700/50 border-b border-gray-100 dark:border-dark-700">
                            <th class="text-left px-3 py-3 font-semibold text-gray-600 dark:text-gray-300 w-8">No</th>
                            <th class="text-left px-3 py-3 font-semibold text-gray-600 dark:text-gray-300">Nama</th>
                            <th class="text-left px-3 py-3 font-semibold text-gray-600 dark:text-gray-300">Paket</th>
                            <th class="text-right px-3 py-3 font-semibold text-gray-600 dark:text-gray-300">Harga</th>
                            <th class="text-right px-3 py-3 font-semibold text-gray-600 dark:text-gray-300">DPP</th>
                            <th class="text-right px-3 py-3 font-semibold text-gray-600 dark:text-gray-300">PPN</th>
                            <th class="text-right px-3 py-3 font-semibold text-gray-600 dark:text-gray-300">BHP USO</th>
                            <th class="text-right px-3 py-3 font-semibold text-gray-600 dark:text-gray-300">Total</th>
                            <th class="text-center px-3 py-3 font-semibold text-gray-600 dark:text-gray-300 w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
                        @foreach ($data as $d)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition" data-id="{{ $d->id }}">
                            <td class="px-3 py-3 text-gray-500 dark:text-gray-400 text-center">{{ $loop->iteration }}</td>
                            <td class="px-3 py-3">
                                <span class="inline-edit font-medium text-gray-800 dark:text-white" data-field="nama_pelanggan" data-id="{{ $d->id }}">{{ $d->nama_pelanggan }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="inline-edit text-gray-700 dark:text-gray-300" data-field="paket" data-id="{{ $d->id }}">{{ $d->paket ?? '-' }}</span>
                            </td>
                            <td class="px-3 py-3 text-right">
                                <span class="inline-edit font-medium text-gray-800 dark:text-white cursor-pointer" data-field="harga" data-id="{{ $d->id }}" data-type="number">{{ number_format($d->harga, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-3 py-3 text-right text-gray-600 dark:text-gray-400 dpp-display" data-id="{{ $d->id }}">Rp{{ number_format($d->dpp, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right text-blue-600 dark:text-blue-400 font-medium ppn-display" data-id="{{ $d->id }}">Rp{{ number_format($d->ppn, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right text-purple-600 dark:text-purple-400 font-medium bhp-display" data-id="{{ $d->id }}">Rp{{ number_format($d->bhp_uso, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right font-semibold text-green-600 dark:text-green-400">Rp{{ number_format($d->harga, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-center">
                                <form method="POST" action="{{ route('repeater.destroy-pelanggan', $d->id) }}" onsubmit="return confirm('Hapus data pelanggan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition p-1">
                                        <i class='bx bx-trash text-lg'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Hapus Bulan -->
            <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700 flex justify-end">
                <form method="POST" action="{{ route('repeater.destroy-bulan', [$r->id, $bulan, $tahun]) }}" onsubmit="return confirm('Hapus semua data bulan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 px-4 py-2 rounded-xl text-sm font-medium transition">
                        <i class='bx bx-trash'></i> Hapus Bulan Ini
                    </button>
                </form>
            </div>
        @else
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-dark-700 flex items-center justify-center">
                    <i class='bx bx-data text-3xl text-gray-400 dark:text-gray-500'></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Tidak ada data</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Belum ada pelanggan untuk bulan ini</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.inline-edit').forEach(el => {
        el.addEventListener('dblclick', function() {
            if (this.querySelector('input')) return;
            const field = this.dataset.field;
            const id = this.dataset.id;
            const type = this.dataset.type || 'text';
            const isHarga = field === 'harga';
            const current = isHarga ? this.textContent.trim().replace(/\./g, '') : this.textContent.trim();

            const input = document.createElement('input');
            input.type = isHarga ? 'number' : 'text';
            input.value = isHarga ? current : current === '-' ? '' : current;
            input.dataset.original = current;
            input.dataset.field = field;
            input.dataset.id = id;
            input.className = 'w-full border border-blue-400 dark:border-blue-600 rounded-lg px-2 py-1 text-sm focus:outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300';
            if (isHarga) input.className += ' text-right';
            input.style.minWidth = isHarga ? '120px' : '150px';

            this.innerHTML = '';
            this.appendChild(input);
            input.focus();
            input.select();

            const save = () => {
                const newVal = input.value.trim();
                if (newVal === input.dataset.original || (isHarga && parseFloat(newVal) === parseFloat(input.dataset.original))) {
                    restoreText(this, field, id, current);
                    return;
                }

                fetch('{{ route("repeater.update-pelanggan", ":id") }}'.replace(':id', id), {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ field, value: newVal })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        if (isHarga) {
                            this.textContent = formatRibuan(res.harga);
                            // update DPP, PPN, BHP USO
                            const row = this.closest('tr');
                            row.querySelector('.dpp-display').textContent = 'Rp' + formatRibuan(res.dpp);
                            row.querySelector('.ppn-display').textContent = 'Rp' + formatRibuan(res.ppn);
                            row.querySelector('.bhp-display').textContent = 'Rp' + formatRibuan(res.bhp_uso);
                            // update kolom Total
                            const cells = row.querySelectorAll('td');
                            if (cells.length >= 8) {
                                cells[7].textContent = 'Rp' + formatRibuan(res.harga);
                            }
                        } else {
                            this.textContent = newVal || '-';
                        }
                        this.dataset.original = newVal;
                    } else {
                        restoreText(this, field, id, current);
                    }
                })
                .catch(() => restoreText(this, field, id, current));
            };

            input.addEventListener('blur', save);
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') { e.preventDefault(); input.blur(); }
                if (e.key === 'Escape') { e.preventDefault(); restoreText(this, field, id, current); }
            });
        });
    });

    function restoreText(el, field, id, current) {
        el.textContent = current || '-';
    }

    function formatRibuan(n) {
        return new Intl.NumberFormat('id-ID').format(n);
    }
</script>
@endpush
