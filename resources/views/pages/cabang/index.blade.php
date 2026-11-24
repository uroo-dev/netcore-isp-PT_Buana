@extends('layouts.app')
@section('title', 'Manajemen Cabang')
@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bxs-buildings text-gray-600 dark:text-blue-400'></i>
                Manajemen Cabang
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola cabang dan akun pengguna cabang</p>
        </div>
        <button data-modal-target="modalTambah" data-modal-toggle="modalTambah"
            class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm flex items-center gap-2 transition-all duration-200">
            <i class='bx bx-plus'></i> Tambah Cabang
        </button>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">#</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Cabang</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Alamat</th>
                        <th class="px-4 py-3 text-left hidden sm:table-cell">Telepon</th>
                        <th class="px-4 py-3 text-center">Jumlah Akun</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-dark-700">
                    @forelse($cabang as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $cabang->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">{{ $item->nama }}</td>
                        <td class="px-4 py-3 hidden md:table-cell text-gray-600 dark:text-gray-400">{{ $item->alamat ?: '-' }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell text-gray-600 dark:text-gray-400">{{ $item->telepon ?: '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-blue-400 px-2.5 py-1 rounded-full text-xs font-medium">{{ $item->users_count }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('cabang.produk', $item->id) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 hover:bg-gray-200 dark:hover:bg-gray-900/50 rounded-lg transition"
                                    title="Lihat Persediaan">
                                    <i class='bx bx-show text-sm'></i>
                                </a>
                                <button data-modal-target="modalEdit{{ $item->id }}" data-modal-toggle="modalEdit{{ $item->id }}"
                                    class="inline-flex items-center justify-center w-8 h-8 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900/50 rounded-lg transition"
                                    title="Edit">
                                    <i class='bx bx-edit text-sm'></i>
                                </button>
                                <form action="{{ route('cabang.destroy', $item->id) }}" method="POST" class="inline" id="delete-cabang-{{ $item->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('delete-cabang-{{ $item->id }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition"
                                        title="Hapus">
                                        <i class='bx bx-trash text-sm'></i>
                                    </button>
                                </form>
                                <a href="{{ route('cabang.cetak-laporan', $item->id) }}" target="_blank"
                                    class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 hover:bg-gray-200 dark:hover:bg-gray-900/50 rounded-lg transition"
                                    title="Cetak Laporan">
                                    <i class='bx bx-printer text-sm'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                            <i class='bx bxs-buildings text-4xl mb-2 block'></i>
                            <p>Belum ada cabang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($cabang->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-dark-700">
            {{ $cabang->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-dark-800 w-full max-w-md rounded-2xl shadow-xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class='bx bx-plus-circle text-gray-600 mr-2'></i>Tambah Cabang
            </h3>
            <button data-modal-hide="modalTambah" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
        </div>
        <form action="{{ route('cabang.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="border-b border-gray-200 dark:border-dark-700 pb-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Data Cabang</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nama Cabang</label>
                        <input type="text" name="nama" required
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Alamat</label>
                        <textarea name="alamat" rows="2"
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Telepon</label>
                        <input type="text" name="telepon"
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                    </div>
                </div>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Akun Pengguna Cabang</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Username</label>
                        <input type="text" name="username" required
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-modal-hide="modalTambah"
                    class="px-4 py-2 bg-gray-200 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
@foreach ($cabang as $item)
<div id="modalEdit{{ $item->id }}" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-dark-800 w-full max-w-md rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Cabang</h3>
            <button data-modal-hide="modalEdit{{ $item->id }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">&times;</button>
        </div>
        <form action="{{ route('cabang.update', $item->id) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nama Cabang</label>
                <input type="text" name="nama" value="{{ $item->nama }}" required
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Alamat</label>
                <textarea name="alamat" rows="2"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">{{ $item->alamat }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Telepon</label>
                <input type="text" name="telepon" value="{{ $item->telepon }}"
                    class="w-full border border-gray-200 dark:border-dark-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-modal-hide="modalEdit{{ $item->id }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@push('scripts')
<script>
function confirmDelete(formId) {
    Swal.fire({
        title: 'Yakin hapus cabang?',
        text: "Akun pengguna cabang akan diubah menjadi pegawai.",
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
@endsection
