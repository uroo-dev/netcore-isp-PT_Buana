@extends('layouts.app')
@section('title', 'Paket WiFi')
@section('content')
    <div class="p-4 md:p-6 space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-cog text-gray-600 dark:text-blue-400 text-2xl'></i>
                Pengaturan
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pengaturan akun dan perusahaan</p>
        </div>

        <!-- Tabel Paket WiFi -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div
                class="px-6 py-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-wifi text-gray-600 dark:text-blue-400 text-xl'></i>
                    Paket WiFi
                    <span
                        class="text-xs bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 px-2 py-0.5 rounded-full">{{ $paketWifi->count() }}</span>
                </h3>
                <button onclick="openModal('modalTambahPaket')"
                    class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-plus text-lg'></i>
                    Tambah Paket
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama Paket</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="px-4 py-3 text-center hidden sm:table-cell">Kecepatan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-dark-700">
                        @forelse($paketWifi as $p)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $p->nama }}</div>
                                    <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $p->kecepatan ? $p->kecepatan . ' Mbps' : '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-blue-400">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center hidden sm:table-cell text-gray-600 dark:text-gray-400">
                                    {{ $p->kecepatan ? $p->kecepatan . ' Mbps' : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button
                                            onclick="openEditModal({{ $p->id }}, '{{ addslashes($p->nama) }}', {{ intval($p->harga) }}, {{ $p->kecepatan !== null ? $p->kecepatan : 'null' }}, '{{ addslashes($p->deskripsi ?? '') }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 rounded-lg transition"
                                            title="Edit">
                                            <i class='bx bx-edit text-sm'></i>
                                        </button>
                                        <form id="delete-form-{{ $p->id }}" action="{{ route('pengaturan.paket.destroy', $p->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            onclick="confirmDeletePaket({{ $p->id }}, '{{ addslashes($p->nama) }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-lg transition"
                                            title="Hapus">
                                            <i class='bx bx-trash text-sm'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <i class='bx bx-wifi-off text-4xl mb-2 block'></i>
                                    <p>Belum ada paket WiFi</p>
                                    <button onclick="openModal('modalTambahPaket')"
                                        class="mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline">
                                        Tambah paket pertama →
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH PAKET -->
    <div id="modalTambahPaket" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-plus-circle text-gray-600 text-xl'></i>
                    Tambah Paket WiFi
                </h3>
                <button onclick="closeModal('modalTambahPaket')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form id="formTambahPaket" action="{{ route('pengaturan.paket.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Paket <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class='bx bx-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="nama" required
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="Contoh: Paket Silver 10 Mbps">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" id="hargaTambah"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-right text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="0">
                        <input type="hidden" name="harga" id="hargaTambahRaw">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Kecepatan (Mbps)
                    </label>
                    <div class="relative">
                        <i class='bx bx-speedometer absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="number" name="kecepatan" min="0" step="any"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="Contoh: 10">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="3"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors resize-none"
                        placeholder="Deskripsi paket (opsional)"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal('modalTambahPaket')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-xl transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT PAKET -->
    <div id="modalEditPaket" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-edit-alt text-amber-600 text-xl'></i>
                    Edit Paket WiFi
                </h3>
                <button onclick="closeModal('modalEditPaket')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form id="formEditPaket" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Paket <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class='bx bx-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="nama" id="edit_nama" required
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" id="edit_harga"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-right text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <input type="hidden" name="harga" id="edit_harga_raw">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Kecepatan (Mbps)
                    </label>
                    <div class="relative">
                        <i class='bx bx-speedometer absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="number" name="kecepatan" id="edit_kecepatan" min="0" step="any"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="3"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal('modalEditPaket')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // ========== FUNGSI MODAL ==========
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }

        // ========== FORMAT RUPIAH ==========
        function formatRupiah(angka) {
            if (isNaN(angka) || angka === null || angka === '') return '0';
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function parseRupiah(str) {
            return parseInt(str.replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
        }

        // Setup format rupiah untuk input harga
        function setupRupiahInput(inputId, hiddenId) {
            const input = document.getElementById(inputId);
            const hidden = document.getElementById(hiddenId);
            if (input && hidden) {
                input.addEventListener('input', function() {
                    let value = parseRupiah(this.value);
                    this.value = formatRupiah(value);
                    hidden.value = value;
                });
            }
        }

        // Setup untuk modal tambah
        setupRupiahInput('hargaTambah', 'hargaTambahRaw');

        // Setup untuk modal edit
        function setupEditRupiah() {
            const editHarga = document.getElementById('edit_harga');
            const editHargaRaw = document.getElementById('edit_harga_raw');
            if (editHarga && editHargaRaw) {
                editHarga.addEventListener('input', function() {
                    let value = parseRupiah(this.value);
                    this.value = formatRupiah(value);
                    editHargaRaw.value = value;
                });
            }
        }

        // ========== EDIT FUNCTION ==========
        function openEditModal(id, nama, harga, kecepatan, deskripsi) {
            const form = document.getElementById('formEditPaket');
            form.action = '/pengaturan/paket/' + id;

            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_harga').value = formatRupiah(harga);
            document.getElementById('edit_harga_raw').value = harga;
            document.getElementById('edit_kecepatan').value = kecepatan || '';
            document.getElementById('edit_deskripsi').value = deskripsi || '';

            setupEditRupiah();
            openModal('modalEditPaket');
        }

        // ========== DELETE WITH SWEETALERT ==========
        function confirmDeletePaket(id, nama) {
            Swal.fire({
                title: 'Yakin hapus paket?',
                html: `Paket "<strong>${nama}</strong>" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = ['modalTambahPaket', 'modalEditPaket'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains('hidden')) {
                        closeModal(modalId);
                    }
                });
            }
        });
    </script>
@endpush
