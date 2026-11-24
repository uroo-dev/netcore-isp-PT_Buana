@extends('layouts.app')

@section('title', 'Manajemen Pegawai')

@section('content')
    <div class="p-4 md:p-6 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bxs-user-account text-gray-600 dark:text-blue-400'></i>
                    Manajemen Pegawai
                </h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola data karyawan perusahaan</p>
            </div>
            <button data-modal-target="modalTambah" data-modal-toggle="modalTambah"
                class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                <i class='bx bx-plus text-lg'></i>
                Tambah Karyawan
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-100 dark:bg-gray-900/30 p-2.5 rounded-xl">
                        <i class='bx bxs-user text-gray-600 dark:text-blue-400 text-xl'></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Karyawan</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalKaryawan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">
                                <i class='bx bx-user mr-1'></i> Nama Lengkap
                            </th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell">
                                <i class='bx bx-phone mr-1'></i> Telepon
                            </th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider hidden md:table-cell">
                                <i class='bx bx-map mr-1'></i> Alamat
                            </th>
                            <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider w-28">
                                <i class='bx bx-cog'></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
                        @forelse($karyawan as $k)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $k->nama_lengkap }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400 hidden sm:table-cell">
                                    {{ $k->telepon ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400 hidden md:table-cell">
                                    {{ $k->alamat ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button onclick="editKaryawan({{ $k->id }}, '{{ addslashes($k->nama_lengkap) }}', '{{ $k->telepon ?? '' }}', '{{ addslashes($k->alamat ?? '') }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 rounded-lg transition"
                                            title="Edit">
                                            <i class='bx bx-edit-alt text-sm'></i>
                                        </button>
                                        <form id="deleteForm{{ $k->id }}" action="{{ route('pegawai.destroy', $k->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete('deleteForm{{ $k->id }}', '{{ addslashes($k->nama_lengkap) }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-lg transition"
                                                title="Hapus">
                                                <i class='bx bx-trash-alt text-sm'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12">
                                    <i class='bx bx-user-x text-5xl text-gray-300 dark:text-gray-600'></i>
                                    <p class="text-gray-400 dark:text-gray-500 mt-3">Belum ada karyawan</p>
                                    <button data-modal-target="modalTambah" data-modal-toggle="modalTambah"
                                        class="mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline">
                                        Tambah karyawan pertama →
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($karyawan->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700">
                    {{ $karyawan->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="modalTambah" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-user-plus text-gray-600 text-xl'></i>
                    Tambah Karyawan
                </h3>
                <button type="button" data-modal-hide="modalTambah" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                        placeholder="Masukkan nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Telepon</label>
                    <input type="text" name="telepon"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                        placeholder="Contoh: 08123456789">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                        placeholder="Alamat (opsional)"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" data-modal-hide="modalTambah"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-700 hover:bg-gray-50 dark:hover:bg-dark-700 rounded-xl transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-xl transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-edit-alt text-amber-600 text-xl'></i>
                    Edit Karyawan
                </h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form id="editKaryawanForm" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" id="edit_nama" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Telepon</label>
                    <input type="text" name="telepon" id="edit_telepon"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alamat</label>
                    <textarea name="alamat" id="edit_alamat" rows="2"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-700 hover:bg-gray-50 dark:hover:bg-dark-700 rounded-xl transition">Batal</button>
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
function confirmDelete(formId, nama) {
    Swal.fire({
        title: 'Yakin hapus ' + nama + '?',
        text: "Data karyawan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

function editKaryawan(id, nama, telepon, alamat) {
    const form = document.getElementById('editKaryawanForm');
    form.action = '/pegawai/' + id;

    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_telepon').value = telepon;
    document.getElementById('edit_alamat').value = alamat;

    const modal = document.getElementById('modalEdit');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('modalEdit');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.fixed.inset-0.z-50').forEach(m => {
            if (m.classList.contains('flex')) {
                m.classList.remove('flex');
                m.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
