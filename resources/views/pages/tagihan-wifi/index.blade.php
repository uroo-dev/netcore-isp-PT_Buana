@extends('layouts.app')

@section('title', 'Tagihan WiFi')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-wifi text-gray-600 dark:text-blue-400'></i>
                Tagihan WiFi
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Buat tagihan WiFi otomatis untuk semua pelanggan aktif</p>
        </div>
        <button data-modal-target="modalBuat" data-modal-toggle="modalBuat"
            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
            <i class='bx bx-plus text-lg'></i>
            Buat Tagihan WiFi
        </button>
    </div>

    @if($preview !== null)
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700">
            <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-show text-gray-600 dark:text-blue-400'></i>
                Preview Tagihan {{ \Carbon\Carbon::parse($filter['tanggal'])->translatedFormat('F Y') }}
            </h3>
            <p class="text-xs text-gray-400 mt-1" id="previewCount">
                {{ count($preview) }} tagihan akan dibuat
                @php $alreadyCount = collect($preview)->where('already_exists', true)->count(); @endphp
                @if($alreadyCount > 0)
                    &mdash; <span class="text-amber-500">{{ $alreadyCount }} sudah punya tagihan bulan ini</span>
                @endif
            </p>
        </div>
        <form id="formPreview" action="{{ route('tagihan-wifi.store') }}" method="POST" onsubmit="bersihkanHarga()">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $filter['tanggal'] }}">
            <input type="hidden" name="jatuh_tempo" value="{{ $filter['jatuh_tempo'] }}">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">#</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Pelanggan</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Tipe</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Keterangan</th>
                            <th class="text-right px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Jumlah</th>
                            <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody id="previewBody" class="divide-y divide-gray-100 dark:divide-dark-700">
                        @forelse($preview as $i => $item)
                            <tr class="preview-row hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150 {{ $item['already_exists'] ? 'bg-amber-50/50 dark:bg-amber-900/10' : '' }}">
                                <td class="px-5 py-3 text-gray-500">{{ $i + 1 }}</td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $item['nama'] }}</div>
                                    @if($item['already_exists'])
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400">
                                                <i class='bx bx-error text-xs'></i>
                                                Sudah ada tagihan bulan ini
                                            </span>
                                        </div>
                                    @endif
                                    @if($item['piutang'] > 0)
                                        <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 piutang-badge-{{ $i }}">
                                                <i class='bx bx-error-circle'></i>
                                                Piutang: Rp {{ number_format($item['piutang'], 0, ',', '.') }}
                                            </span>
                                            <button type="button" onclick="batalkanPiutang({{ $i }}, {{ $item['piutang'] }}, {{ $item['harga_pokok'] }})"
                                                class="text-xs text-red-500 hover:text-red-700 underline piutang-cancel-{{ $i }}">
                                                Batalkan Piutang
                                            </button>
                                        </div>
                                    @endif
                                    <input type="hidden" name="items[{{ $i }}][pelanggan_id]" value="{{ $item['pelanggan_id'] }}">
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $item['tipe'] === 'individu' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : '' }}
                                        {{ $item['tipe'] === 'instansi' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : '' }}
                                        {{ $item['tipe'] === 'mitra' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' : '' }}">
                                        {{ ucfirst($item['tipe']) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <input type="text" name="items[{{ $i }}][keterangan]" id="ket-{{ $i }}" value="{{ $item['keterangan'] }}"
                                        class="w-full border border-gray-200 dark:border-dark-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                                </td>
                                <td class="px-5 py-3">
                                    <input type="text" name="items[{{ $i }}][harga]" id="harga-{{ $i }}" value="Rp {{ number_format($item['harga'], 0, ',', '.') }}"
                                        class="w-40 text-right border border-gray-200 dark:border-dark-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 font-semibold harga-input"
                                        oninput="formatRupiah(this)">
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <button type="button" onclick="hapusBaris(this)"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200"
                                        title="Batalkan">
                                        <i class='bx bx-x text-xl'></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyPreview">
                                <td colspan="6" class="text-center py-12">
                                    <i class='bx bx-check-circle text-5xl text-green-400'></i>
                                    <p class="text-gray-400 dark:text-gray-500 mt-3">Semua pelanggan sudah memiliki tagihan bulan ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($preview) > 0)
                    <tfoot class="bg-gray-50 dark:bg-dark-700/50 border-t border-gray-200 dark:border-dark-700">
                        <tr>
                            <td colspan="5" class="px-5 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">Total Keseluruhan</td>
                            <td class="px-5 py-3 text-right font-bold text-gray-800 dark:text-white" id="totalPreview">
                                Rp {{ number_format(collect($preview)->sum('harga'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700 flex justify-end gap-3" id="previewFooter">
                <a href="{{ route('tagihan-wifi.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                    Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-save text-lg'></i>
                    Simpan Semua (<span id="countLabel">{{ count($preview) }}</span>)
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-list-ul text-gray-600 dark:text-blue-400'></i>
                Riwayat Tagihan WiFi
            </h3>
            <form method="GET" action="{{ route('tagihan-wifi.index') }}" id="wifiFilterForm" class="flex items-center gap-2">
                <input type="month" name="bulan" value="{{ $bulan }}"
                    class="border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-gray-500/20 bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300">
                <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded-xl text-sm transition">
                    <i class='bx bx-filter-alt'></i>
                </button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                    <tr>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">No Nota</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Pelanggan</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Total</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Sisa</th>
                        <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="wifiTbody" class="divide-y divide-gray-100 dark:divide-dark-700">
                    @include('pages.tagihan-wifi.partials.rows')
                </tbody>
            </table>
        </div>
        <div id="wifiPagination" class="border-t border-gray-100 dark:border-dark-700">
            @include('pages.tagihan-wifi.partials.pagination')
        </div>
    </div>
</div>

<!-- Modal Info Pajak -->
<div id="modalPajak" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
    onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-calculator text-amber-600'></i>
                Info Pajak
            </h3>
            <button onclick="this.closest('#modalPajak').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
        </div>
        <div class="space-y-3 text-sm" id="pajakContent">
            <p class="text-gray-500 dark:text-gray-400 text-xs" id="pajakNota"></p>
            <div class="bg-gray-50 dark:bg-dark-700/50 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Harga Paket</span>
                    <span class="font-semibold text-gray-800 dark:text-white" id="pajakTotal">Rp 0</span>
                </div>
                <hr class="border-gray-200 dark:border-dark-700">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>DPP (Total ÷ 1,11)</span>
                    <span class="font-semibold text-gray-800 dark:text-white" id="pajakDpp">Rp 0</span>
                </div>
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>PPN (Total - DPP)</span>
                    <span class="font-semibold text-gray-800 dark:text-white" id="pajakPpn">Rp 0</span>
                </div>
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>BHP USO (DPP × 1,75%)</span>
                    <span class="font-semibold text-gray-800 dark:text-white" id="pajakBhp">Rp 0</span>
                </div>
                <hr class="border-gray-200 dark:border-dark-700">
                <div class="flex justify-between text-amber-600 dark:text-amber-400 font-semibold">
                    <span>Proyeksi Pajak</span>
                    <span id="pajakProyeksi">Rp 0</span>
                </div>
            </div>
            <p class="text-xs text-gray-400 italic">*Hanya estimasi, tidak mempengaruhi total tagihan</p>
        </div>
    </div>
</div>

<!-- Modal Buat Tagihan -->
<div id="modalBuat" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Buat Tagihan WiFi</h3>
            <button type="button" data-modal-hide="modalBuat"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form action="{{ route('tagihan-wifi.index') }}" method="GET">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Pelanggan</label>
                    <select name="kategori" id="kategori" required onchange="togglePJ()"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="semua">Semua Pelanggan Aktif</option>
                        <option value="individu">Individu</option>
                        <option value="instansi">Instansi</option>
                        <option value="mitra">Mitra</option>
                        <option value="pj">Per Penanggung Jawab</option>
                    </select>
                </div>
                <div id="pjGroup" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penanggung Jawab</label>
                    <select name="pj_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">-- Pilih PJ --</option>
                        @foreach($penanggungJawab as $pj)
                            <option value="{{ $pj->id }}">{{ $pj->nama }} ({{ $pj->wilayah ?? 'All' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Penarikan</label>
                    <input type="date" name="tanggal" required value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" required value="{{ old('jatuh_tempo', now()->addMonth()->format('Y-m-d')) }}"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" data-modal-hide="modalBuat"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-xl transition shadow-sm">
                    Lihat Preview
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showModalPajak(nota, total) {
    const dpp = total / 1.11;
    const ppn = total - dpp;
    const bhp = dpp * 0.0175;
    const proyeksi = bhp + ppn;

    document.getElementById('pajakNota').textContent = 'Nota: ' + nota;
    document.getElementById('pajakTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(total));
    document.getElementById('pajakDpp').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(dpp));
    document.getElementById('pajakPpn').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(ppn));
    document.getElementById('pajakBhp').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(bhp));
    document.getElementById('pajakProyeksi').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(proyeksi));

    const modal = document.getElementById('modalPajak');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function togglePJ() {
    const kategori = document.getElementById('kategori').value;
    document.getElementById('pjGroup').classList.toggle('hidden', kategori !== 'pj');
}
document.addEventListener('DOMContentLoaded', function() {
    togglePJ();
});

function confirmHapus(btn) {
    Swal.fire({
        title: 'Yakin hapus tagihan ini?',
        text: "Tagihan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            btn.closest('form').submit();
        }
    });
}

function formatRupiah(input) {
    let val = input.value.replace(/[^0-9]/g, '');
    if (val === '') { input.value = ''; return; }
    let formatted = new Intl.NumberFormat('id-ID').format(val);
    input.value = 'Rp ' + formatted;
}

function hitungTotal() {
    const inputs = document.querySelectorAll('.harga-input');
    let total = 0;
    inputs.forEach(inp => {
        let num = parseInt(inp.value.replace(/[^0-9]/g, '')) || 0;
        total += num;
    });
    const el = document.getElementById('totalPreview');
    if (el) el.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('harga-input')) {
        hitungTotal();
    }
});

function batalkanPiutang(index, piutang, hargaPokok) {
    const hargaInput = document.getElementById('harga-' + index);
    const ketInput = document.getElementById('ket-' + index);
    const badge = document.querySelector('.piutang-badge-' + index);
    const cancelBtn = document.querySelector('.piutang-cancel-' + index);

    let formatted = new Intl.NumberFormat('id-ID').format(hargaPokok);
    hargaInput.value = 'Rp ' + formatted;

    ketInput.value = ketInput.value.replace(/ \+ Piutang.*/, '');

    if (badge) badge.remove();
    if (cancelBtn) cancelBtn.remove();

    hitungTotal();
}

function hapusBaris(btn) {
    const row = btn.closest('tr');
    row.remove();
    hitungTotal();
    const rows = document.querySelectorAll('#previewBody .preview-row');
    const count = rows.length;
    document.getElementById('previewCount').innerText = count + ' tagihan akan dibuat';
    const label = document.getElementById('countLabel');
    if (label) label.innerText = count;
    if (count === 0) {
        document.getElementById('previewFooter')?.classList.add('hidden');
        const tfoot = document.querySelector('#previewBody').closest('table').querySelector('tfoot');
        if (tfoot) tfoot.classList.add('hidden');
    }
}

function bersihkanHarga() {
    document.querySelectorAll('.harga-input').forEach(inp => {
        inp.value = inp.value.replace(/[^0-9]/g, '');
    });
}

// AJAX Filter bulan Riwayat Tagihan
const wifiForm = document.getElementById('wifiFilterForm');
const wifiTbody = document.getElementById('wifiTbody');
const wifiPagination = document.getElementById('wifiPagination');

async function fetchWifi(page = null) {
    const params = new URLSearchParams(new FormData(wifiForm));
    if (page) params.set('page', page);

    try {
        const res = await fetch('{{ route('tagihan-wifi.data') }}' + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) return;
        const data = await res.json();
        wifiTbody.innerHTML = data.rows;
        wifiPagination.innerHTML = data.pagination;
        bindWifiPagination();
    } catch (e) {
        window.location.href = '{{ route('tagihan-wifi.index') }}' + '?' + params.toString();
    }
}

function bindWifiPagination() {
    wifiPagination.querySelectorAll('a[href]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(link.href);
            fetchWifi(url.searchParams.get('page'));
        });
    });
}

wifiForm.addEventListener('submit', function(e) {
    e.preventDefault();
    fetchWifi(1);
});

bindWifiPagination();
</script>
@endpush
