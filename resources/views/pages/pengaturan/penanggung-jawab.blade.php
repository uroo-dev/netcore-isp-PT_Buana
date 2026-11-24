@extends('layouts.app')
@section('title', 'Penanggung Jawab')
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

        <!-- Tabel Penanggung Jawab -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div
                class="px-6 py-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-user-pin text-gray-600 dark:text-blue-400 text-xl'></i>
                    Penanggung Jawab WiFi per Wilayah
                    <span
                        class="text-xs bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 px-2 py-0.5 rounded-full">{{ $penanggungJawab->count() }}</span>
                </h3>
                <button onclick="openModal('modalTambahPJ')"
                    class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class='bx bx-plus text-lg'></i>
                    Tambah Penanggung Jawab
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left hidden sm:table-cell">Telepon</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Wilayah</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-dark-700">
                        @forelse($penanggungJawab as $pj)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $pj->nama }}</div>
                                    <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $pj->telepon ?? '-' }}
                                    </div>
                                    <div class="md:hidden text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $pj->wilayah ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 hidden sm:table-cell text-gray-600 dark:text-gray-400">
                                    {{ $pj->telepon ?? '-' }}
                                </td>
                                <td class="px-4 py-3 hidden md:table-cell text-gray-600 dark:text-gray-400">
                                    <span
                                        class="inline-flex items-center gap-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 rounded-full text-xs">
                                        <i class='bx bx-map text-xs'></i> {{ $pj->wilayah ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button
                                            onclick="openEditModal({{ $pj->id }}, '{{ addslashes($pj->nama) }}', '{{ $pj->telepon ?? '' }}', '{{ addslashes($pj->wilayah ?? '') }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 rounded-lg transition"
                                            title="Edit">
                                            <i class='bx bx-edit text-sm'></i>
                                        </button>
                                        <button type="button"
                                            onclick="confirmDelete('{{ route('pengaturan.penanggung-jawab.destroy', $pj->id) }}', '{{ addslashes($pj->nama) }}')"
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
                                    <i class='bx bx-user-x text-4xl mb-2 block'></i>
                                    <p>Belum ada penanggung jawab</p>
                                    <button onclick="openModal('modalTambahPJ')"
                                        class="mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline">
                                        Tambah penanggung jawab pertama →
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH PENANGGUNG JAWAB -->
    <div id="modalTambahPJ" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-user-plus text-gray-600 text-xl'></i>
                    Tambah Penanggung Jawab
                </h3>
                <button onclick="closeModal('modalTambahPJ')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('pengaturan.penanggung-jawab.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class='bx bx-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="nama" required
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="Nama lengkap penanggung jawab">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Telepon
                    </label>
                    <div class="relative">
                        <i class='bx bx-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="telepon"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="Contoh: 08123456789">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Wilayah
                    </label>
                    <div class="relative">
                        <i class='bx bx-map absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="wilayah"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="Contoh: Jakarta Selatan">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal('modalTambahPJ')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-xl transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT PENANGGUNG JAWAB -->
    <div id="modalEditPJ" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-edit-alt text-amber-600 text-xl'></i>
                    Edit Penanggung Jawab
                </h3>
                <button onclick="closeModal('modalEditPJ')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form id="formEditPJ" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class='bx bx-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="nama" id="edit_nama" required
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Telepon
                    </label>
                    <div class="relative">
                        <i class='bx bx-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="telepon" id="edit_telepon"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Wilayah
                    </label>
                    <div class="relative">
                        <i class='bx bx-map absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="wilayah" id="edit_wilayah"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                            placeholder="Contoh: Jakarta Selatan">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal('modalEditPJ')"
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

        // ========== EDIT FUNCTION ==========
        function openEditModal(id, nama, telepon, wilayah) {
            const form = document.getElementById('formEditPJ');
            form.action = '/pengaturan/penanggung-jawab/' + id;

            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_telepon').value = telepon || '';
            document.getElementById('edit_wilayah').value = wilayah || '';

            openModal('modalEditPJ');
        }

        // ========== DELETE WITH SWEETALERT ==========
        function confirmDelete(deleteUrl, nama) {
            Swal.fire({
                title: 'Yakin hapus penanggung jawab?',
                html: `Data "<strong>${nama}</strong>" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    form.innerHTML = '@csrf @method('DELETE')';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = ['modalTambahPJ', 'modalEditPJ'];
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
