@extends('layouts.app')

@section('title', 'Manajemen Akun')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bxs-user-circle text-gray-600 dark:text-blue-400'></i>
                Manajemen Akun
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                Kelola akun login {{ $totalAkun }} akun
            </p>
        </div>
        <button data-modal-target="modalTambah" data-modal-toggle="modalTambah"
            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
            <i class='bx bx-plus text-lg'></i>
            Tambah Akun
        </button>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                    <tr>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Nama Lengkap</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Username</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Telepon</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Role</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Cabang</th>
                        <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-800 dark:text-white">{{ $user->nama_lengkap }}</div>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $user->username }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $user->telepon ?? '-' }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $roleClass = match($user->role) {
                                        'owner' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                                        'pegawai' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                        'cabang' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
                                        default => 'bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $roleClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $user->cabang?->nama ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editAkun({{ $user->id }}, '{{ addslashes($user->nama_lengkap) }}', '{{ addslashes($user->username) }}', '{{ addslashes($user->telepon ?? '') }}', '{{ $user->role }}', '{{ $user->cabang_id ?? '' }}')"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all duration-200"
                                        title="Edit">
                                        <i class='bx bx-edit-alt text-lg'></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form id="deleteForm{{ $user->id }}" action="{{ route('akun.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete('deleteForm{{ $user->id }}', '{{ addslashes($user->nama_lengkap) }}')"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-all duration-200"
                                            title="Hapus">
                                            <i class='bx bx-trash-alt text-lg'></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <i class='bx bx-user-x text-5xl text-gray-300 dark:text-gray-600'></i>
                                <p class="text-gray-400 dark:text-gray-500 mt-3">Belum ada akun</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Tambah Akun</h3>
            <button type="button" data-modal-hide="modalTambah"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form action="{{ route('akun.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                        placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                    <input type="text" name="username" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                        placeholder="Username untuk login">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                    <input type="text" name="telepon"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                        placeholder="Nomor telepon">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                        placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                    <select name="role" required id="roleTambah"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="pegawai">Pegawai</option>
                        <option value="cabang">Cabang</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
                <div id="cabangTambah">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cabang</label>
                    <select name="cabang_id"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">-- Pilih Cabang --</option>
                        @foreach($cabangList as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" data-modal-hide="modalTambah"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-xl transition shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Edit Akun</h3>
            <button type="button" onclick="tutupEdit()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formEdit" action="" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="edit_nama" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                    <input type="text" name="username" id="edit_username" required
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                    <input type="text" name="telepon" id="edit_telepon"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password <span class="text-xs text-gray-400">(kosongi jika tidak diubah)</span></label>
                    <input type="password" name="password" id="edit_password"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                        placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                    <select name="role" id="edit_role" required onchange="toggleCabangEdit()"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="pegawai">Pegawai</option>
                        <option value="cabang">Cabang</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
                <div id="cabangEdit">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cabang</label>
                    <select name="cabang_id" id="edit_cabang"
                        class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                        <option value="">-- Pilih Cabang --</option>
                        @foreach($cabangList as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="tutupEdit()"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-xl hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-xl transition shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleCabangTambah() {
    const role = document.getElementById('roleTambah').value;
    document.getElementById('cabangTambah').style.display = role === 'cabang' ? '' : 'none';
}
function toggleCabangEdit() {
    const role = document.getElementById('edit_role').value;
    document.getElementById('cabangEdit').style.display = role === 'cabang' ? '' : 'none';
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('roleTambah').addEventListener('change', toggleCabangTambah);
    toggleCabangTambah();
    toggleCabangEdit();
});

function editAkun(id, nama, username, telepon, role, cabangId) {
    document.getElementById('formEdit').action = '/akun/' + id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_telepon').value = telepon;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_cabang').value = cabangId;
    toggleCabangEdit();
    document.getElementById('modalEdit').classList.remove('hidden');
    document.getElementById('modalEdit').classList.add('flex');
}

function tutupEdit() {
    document.getElementById('modalEdit').classList.add('hidden');
    document.getElementById('modalEdit').classList.remove('flex');
}

function confirmDelete(formId, nama) {
    Swal.fire({
        title: 'Yakin hapus ' + nama + '?',
        text: "Akun akan dihapus permanen!",
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
</script>
@endpush
