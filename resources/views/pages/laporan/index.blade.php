@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bxs-bar-chart-square text-gray-600 dark:text-blue-400 text-2xl'></i>
                    Laporan Perusahaan
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Laporan keuangan dan penjualan</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="toggleMoney()"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-600 dark:hover:text-blue-400"
                    id="moneyToggleBtn">
                    <i class='bx bx-show text-lg' id="moneyToggleIcon"></i>
                    <span id="moneyToggleLabel">Sembunyikan Jumlah</span>
                </button>
                <form method="GET" id="laporanFilterForm" class="flex gap-2 items-end">
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Periode</label>
                        <input type="month" name="bulan" id="laporanBulanInput" value="{{ $bulan }}"
                            data-current="{{ now()->format('Y-m') }}"
                            class="border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                    <button type="submit"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-1">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" id="laporanResetBtn"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-700 transition-all">
                        <i class='bx bx-reset mr-1'></i> Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- Ringkasan Cards -->
        <div id="laporanCards">
            @include('pages.laporan.partials.cards')
        </div>

        <!-- Chart Line -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md p-5 border border-gray-100 dark:border-dark-700 transition-colors duration-200">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class='bx bx-line-chart text-gray-600 text-xl'></i>
                Grafik Keuangan Bulanan <span id="laporanTahun">{{ $tahun }}</span>
            </h3>
            <div class="relative">
                <canvas id="chartKeuangan" height="100"></canvas>
            </div>
        </div>

        <!-- Tabel Bawah (2 Kolom) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tabel Tagihan -->
            <div id="laporanInvoices">
                @include('pages.laporan.partials.invoices')
            </div>

            <!-- Laba Rugi -->
            <div id="laporanLabaRugi">
                @include('pages.laporan.partials.laba-rugi')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        const ctx = document.getElementById('chartKeuangan')?.getContext('2d');

        if (ctx) {
            window.laporanChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: bulan,
                    datasets: [{
                            label: 'Pemasukan',
                            data: @json($penjualanBulanan),
                            borderColor: 'rgba(34, 197, 94, 1)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                            pointBorderColor: '#fff',
                            pointRadius: 3,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($pengeluaranBulanan),
                            borderColor: 'rgba(239, 68, 68, 1)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                            pointBorderColor: '#fff',
                            pointRadius: 3,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Piutang',
                            data: @json($piutangBulanan),
                            borderColor: 'rgba(249, 115, 22, 1)',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(249, 115, 22, 1)',
                            pointBorderColor: '#fff',
                            pointRadius: 3,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.raw;
                                    return label + ': Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                    }
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Dark mode chart update
        if (typeof window !== 'undefined') {
            window.addEventListener('themeChanged', function(e) {
                const isDark = e.detail.theme === 'dark';
                const chart = Chart.getChart('chartKeuangan');
                if (chart) {
                    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
                    chart.options.scales.y.grid.color = gridColor;
                    chart.update();
                }
            });
        }

        // AJAX filter bulan
        const laporanBase = '{{ route('laporan.data') }}';

        async function fetchLaporan(url) {
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('Gagal memuat data');
            const data = await res.json();

            document.getElementById('laporanCards').innerHTML = data.cards;
            document.getElementById('laporanInvoices').innerHTML = data.invoices;
            document.getElementById('laporanLabaRugi').innerHTML = data.labaRugi;
            document.getElementById('laporanTahun').textContent = data.tahun;

            if (window.laporanChart) {
                window.laporanChart.data.datasets[0].data = data.penjualanBulanan;
                window.laporanChart.data.datasets[1].data = data.pengeluaranBulanan;
                window.laporanChart.data.datasets[2].data = data.piutangBulanan;
                window.laporanChart.update();
            }
            bindLaporanPagination();
            if (typeof applyMoneyState === 'function') applyMoneyState();
        }

        function bindLaporanPagination() {
            const container = document.getElementById('laporanInvoices');
            if (!container) return;
            const paginationEl = container.querySelector('#laporanPagination');
            if (!paginationEl) return;
            paginationEl.querySelectorAll('a[href]').forEach(function(a) {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchLaporan(this.href);
                });
            });
        }

        document.getElementById('laporanFilterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const bulan = this.querySelector('[name="bulan"]').value;
            const url = laporanBase + '?' + new URLSearchParams({ bulan: bulan });
            fetchLaporan(url);
        });

        document.getElementById('laporanResetBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const input = document.getElementById('laporanBulanInput');
            if (input) input.value = input.dataset.current || '';
            fetchLaporan(laporanBase);
        });

        bindLaporanPagination();
    </script>
@endpush
