@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Statistik Cards Utama - dengan tombol mata per kartu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Pelanggan (tanpa mata karena bukan nominal) -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 hover:shadow-lg transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Pelanggan</p>
                    <div class="bg-gray-100 dark:bg-gray-900/30 p-2.5 rounded-xl text-gray-700 dark:text-blue-400">
                        <i class='bx bx-group text-2xl'></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($totalPelanggan) }}</h3>
                <div class="flex gap-3 mt-2 text-xs">
                    <span class="text-green-600 dark:text-green-400">✓ {{ $totalPelangganOn }} Aktif</span>
                    <span class="text-red-600 dark:text-red-400">✗ {{ $totalPelangganOff }} Off</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Tagihan Bulan Ini (dengan tombol mata) -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 hover:shadow-lg transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Tagihan Bulan Ini</p>
                    <div class="flex items-center gap-2">
                        <button onclick="toggleCardMoney(this, 'tagihanValue')" class="money-toggle-btn p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-blue-400 transition">
                            <i class='bx bx-hide text-xl'></i>
                        </button>
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-2.5 rounded-xl text-yellow-700 dark:text-yellow-400">
                            <i class='bx bx-credit-card text-2xl'></i>
                        </div>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <span class="money-value-card" id="tagihanValue" data-amount="Rp {{ number_format($invoiceBulanIni, 0, ',', '.') }}">Rp {{ number_format($invoiceBulanIni, 0, ',', '.') }}</span>
                </h3>
            </div>
        </div>

        <!-- Card 3: Saldo Keuangan (dengan tombol mata) -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 hover:shadow-lg transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Saldo Keuangan</p>
                    <div class="flex items-center gap-2">
                        <button onclick="toggleCardMoney(this, 'saldoValue')" class="money-toggle-btn p-1.5 rounded-lg text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                            <i class='bx bx-hide text-xl'></i>
                        </button>
                        <div class="bg-emerald-100 dark:bg-emerald-900/30 p-2.5 rounded-xl text-emerald-700 dark:text-emerald-400">
                            <i class='bx bx-wallet text-2xl'></i>
                        </div>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <span class="money-value-card" id="saldoValue" data-amount="Rp {{ number_format($saldoKas, 0, ',', '.') }}">Rp {{ number_format($saldoKas, 0, ',', '.') }}</span>
                </h3>
            </div>
        </div>

        <!-- Card 4: Total Piutang (dengan tombol mata) -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 hover:shadow-lg transition-all duration-200 overflow-hidden group">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Piutang</p>
                    <div class="flex items-center gap-2">
                        <button onclick="toggleCardMoney(this, 'piutangValue')" class="money-toggle-btn p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition">
                            <i class='bx bx-hide text-xl'></i>
                        </button>
                        <div class="bg-red-100 dark:bg-red-900/30 p-2.5 rounded-xl text-red-700 dark:text-red-400">
                            <i class='bx bx-error text-2xl'></i>
                        </div>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">
                    <span class="money-value-card" id="piutangValue" data-amount="Rp {{ number_format($totalPiutang, 0, ',', '.') }}">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
                </h3>
            </div>
        </div>
    </div>

    <!-- Statistik Ringkas (tanpa duplikasi data) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-dark-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Pendapatan Bulan Ini</p>
                    <p class="text-lg font-bold text-green-600 dark:text-green-400">
                        <span class="money-value-simple" id="pendapatanValue" data-amount="Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</span>
                    </p>
                </div>
                <button onclick="toggleSimpleMoney('pendapatanValue')" class="money-toggle-simple p-1 rounded text-gray-400 hover:text-green-600">
                    <i class='bx bx-hide text-lg'></i>
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-dark-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Pengeluaran Bulan Ini</p>
                    <p class="text-lg font-bold text-red-600 dark:text-red-400">
                        <span class="money-value-simple" id="pengeluaranValue" data-amount="Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</span>
                    </p>
                </div>
                <button onclick="toggleSimpleMoney('pengeluaranValue')" class="money-toggle-simple p-1 rounded text-gray-400 hover:text-red-600">
                    <i class='bx bx-hide text-lg'></i>
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-dark-700">
            <p class="text-gray-400 text-xs uppercase tracking-wide">Stok Menipis</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $produkMenipis }}</p>
            <p class="text-xs text-gray-500">+ {{ $produkHabis }} habis</p>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-dark-700">
            <p class="text-gray-400 text-xs uppercase tracking-wide">Pelanggan</p>
            <div class="flex justify-between items-baseline">
                <p class="text-2xl font-bold text-gray-600 dark:text-blue-400">{{ $totalPelangganOn }}</p>
                <p class="text-sm text-gray-500">Aktif</p>
                <p class="text-xl font-bold text-red-500">{{ $totalPelangganOff }}</p>
                <p class="text-sm text-gray-500">Off</p>
            </div>
        </div>
    </div>

    <!-- Grafik & Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Penjualan -->
        <div class="lg:col-span-2 bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class='bx bx-bar-chart-alt-2 text-gray-600 dark:text-blue-400'></i> Grafik Penjualan 6 Bulan Terakhir
            </h3>
            <canvas id="chartPenjualan" height="120"></canvas>
        </div>

        <!-- Pelanggan Off Bulanan -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class='bx bx-user-x text-red-500 dark:text-red-400'></i> Pelanggan Off per Bulan
            </h3>
            <canvas id="chartOffPelanggan" height="120"></canvas>
        </div>
    </div>

    <!-- Dua Kolom: Pelanggan Terbaru & Tagihan Belum Lunas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pelanggan Terbaru -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class='bx bx-user-plus text-gray-600 dark:text-blue-400'></i> Pelanggan Terbaru
            </h3>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse($pelangganBaru as $p)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-dark-700 rounded-lg transition">
                    <div class="bg-gray-100 dark:bg-gray-900/40 w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-gray-600 dark:text-blue-400 font-bold">{{ substr($p->nama, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-gray-800 dark:text-white truncate">{{ $p->nama }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ ucfirst($p->tipe) }}</p>
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $p->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center py-4">Belum ada pelanggan baru</p>
                @endforelse
            </div>
            <a href="{{ route('pelanggan.index') }}" class="mt-4 text-gray-600 dark:text-blue-400 text-sm hover:underline block text-center">Lihat semua pelanggan →</a>
        </div>

        <!-- Tagihan Belum Lunas -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class='bx bx-error-circle text-orange-500 dark:text-orange-400'></i> Tagihan Belum Lunas
                <span class="ml-2 text-xs bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 px-2 py-0.5 rounded-full">{{ $invoiceBelumLunas->count() }}</span>
            </h3>
            @if($invoiceBelumLunas->count() > 0)
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-dark-700 text-gray-600 dark:text-gray-300 border-b dark:border-dark-600 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left">No Nota</th>
                            <th class="px-3 py-2 text-left">Pelanggan</th>
                            <th class="px-3 py-2 text-center">Jatuh Tempo</th>
                            <th class="px-3 py-2 text-right">Sisa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-dark-700">
                        @foreach($invoiceBelumLunas->take(5) as $inv)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/50 transition">
                            <td class="px-3 py-2 font-mono text-xs">
                                <a href="{{ route('invoice.show', $inv->id) }}" class="text-gray-600 dark:text-blue-400 hover:underline">{{ $inv->kode_invoice }}</a>
                            </td>
                            <td class="px-3 py-2 text-gray-700 dark:text-gray-300">{{ $inv->pelanggan->nama }}</td>
                            <td class="px-3 py-2 text-center text-gray-500 dark:text-gray-400 text-xs">{{ $inv->jatuh_tempoJ ?? '-' }}</td>
                            <td class="px-3 py-2 text-right font-semibold text-red-600 dark:text-red-400">
                                <span class="invoice-money" id="invoice_{{ $inv->id }}" data-amount="Rp {{ number_format($inv->sisa, 0, ',', '.') }}">Rp {{ number_format($inv->sisa, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($invoiceBelumLunas->count() > 5)
                <div class="mt-3 text-center">
                    <a href="{{ route('invoice.index', ['status' => 'belum_lunas']) }}" class="text-gray-600 dark:text-blue-400 text-xs hover:underline">+ {{ $invoiceBelumLunas->count() - 5 }} tagihan lainnya</a>
                </div>
                @endif
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <i class='bx bx-check-circle text-4xl mb-2'></i>
                <p>Semua tagihan sudah lunas</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ========== FUNGSI TOGGLE MONEY PER KARTU ==========
// Untuk kartu besar dengan tombol mata di dalam card
function toggleCardMoney(button, elementId) {
    const span = document.getElementById(elementId);
    const icon = button.querySelector('i');

    if (!span || !icon) return;

    const isHidden = span.getAttribute('data-hidden') === 'true';

    if (isHidden) {
        // Tampilkan nominal asli
        span.textContent = span.getAttribute('data-amount');
        span.setAttribute('data-hidden', 'false');
        icon.classList.remove('bx-show');
        icon.classList.add('bx-hide');
    } else {
        // Sembunyikan nominal
        span.setAttribute('data-hidden', 'true');
        span.textContent = 'Rp ●●●●';
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show');
    }
}

// Untuk kartu ringkas (pendapatan/pengeluaran)
function toggleSimpleMoney(elementId) {
    const span = document.getElementById(elementId);
    if (!span) return;

    const isHidden = span.getAttribute('data-hidden') === 'true';

    if (isHidden) {
        span.textContent = span.getAttribute('data-amount');
        span.setAttribute('data-hidden', 'false');
        // update icon di tombol yang sesuai (cari tombol terdekat)
        const parent = span.closest('.flex.items-center.justify-between');
        if (parent) {
            const btn = parent.querySelector('.money-toggle-simple i');
            if (btn) {
                btn.classList.remove('bx-show');
                btn.classList.add('bx-hide');
            }
        }
    } else {
        span.setAttribute('data-hidden', 'true');
        span.textContent = 'Rp ●●●●';
        const parent = span.closest('.flex.items-center.justify-between');
        if (parent) {
            const btn = parent.querySelector('.money-toggle-simple i');
            if (btn) {
                btn.classList.remove('bx-hide');
                btn.classList.add('bx-show');
            }
        }
    }
}

// Inisialisasi semua data-amount pada elemen money-value-card dan money-value-simple
document.addEventListener('DOMContentLoaded', function() {
    // Set data-hidden attribute default = false untuk semua
    document.querySelectorAll('.money-value-card, .money-value-simple, .invoice-money').forEach(el => {
        if (!el.getAttribute('data-amount')) {
            el.setAttribute('data-amount', el.textContent);
        }
        el.setAttribute('data-hidden', 'false');
    });

    // Chart Penjualan
    const ctx1 = document.getElementById('chartPenjualan');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($grafikLabel),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: @json($grafikPenjualan),
                    backgroundColor: 'rgba(107, 114, 128, 0.7)',
                    borderColor: 'rgba(107, 114, 128, 1)',
                    borderWidth: 1,
                    borderRadius: 8,
                    barPercentage: 0.65
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart Pelanggan Off
    const ctx2 = document.getElementById('chartOffPelanggan');
    if (ctx2) {
        new Chart(ctx2.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($grafikLabel),
                datasets: [{
                    label: 'Pelanggan Off',
                    data: @json($pelangganOffBulanan),
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' pelanggan off';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    }
                }
            }
        });
    }
});
</script>

<style>
/* Dark mode support untuk chart (opsional) */
.dark-mode canvas {
    filter: brightness(0.9) contrast(1.1);
}

/* Transisi smooth untuk tombol mata */
.money-toggle-btn i, .money-toggle-simple i {
    transition: all 0.2s ease;
}

.money-toggle-btn:hover i, .money-toggle-simple:hover i {
    transform: scale(1.1);
}

/* Scrollbar custom untuk container */
.max-h-80::-webkit-scrollbar {
    width: 4px;
}
.max-h-80::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
.dark-mode .max-h-80::-webkit-scrollbar-track {
    background: #2d2d3a;
}
.max-h-80::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
.dark-mode .max-h-80::-webkit-scrollbar-thumb {
    background: #525265;
}
</style>
@endpush
