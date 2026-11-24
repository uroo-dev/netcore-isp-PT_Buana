@extends('layouts.app')
@section('title', 'Pengaturan')
@section('content')
    <div class="p-6 space-y-6 max-w-4xl mx-auto">
        <div>
            <h1 class="font-bold text-xl text-gray-800 flex items-center gap-2">
                <i class='bx bx-cog text-gray-600'></i> Pengaturan
            </h1>
            <p class="text-gray-500 text-sm">Pengaturan akun dan perusahaan</p>
        </div>

        <!-- Profil Akun -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                <i class='bx bx-user text-gray-600'></i> Pengaturan Akun
            </h3>
            <form action="{{ route('pengaturan.profil') }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}" required
                            class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Username</label>
                        <input type="text" name="username" value="{{ $user->username }}" required
                            class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Telepon</label>
                        <input type="text" name="telepon" value="{{ $user->telepon ?? '' }}"
                            class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Role</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" disabled
                            class="w-full mt-1 border rounded-lg px-3 py-2 text-sm bg-gray-100">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                            class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-gray-700">
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
        @if (auth()->user()->role == 'owner')
            <!-- Pengaturan Perusahaan -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    <i class='bx bx-building text-gray-600'></i> Pengaturan Perusahaan
                </h3>
                <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Nama Perusahaan</label>
                            <input type="text" name="nama" value="{{ $pengaturan?->nama ?? '' }}" required
                                class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-medium">Email</label>
                            <input type="email" name="email" value="{{ $pengaturan?->email ?? '' }}"
                                class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-medium">Telepon</label>
                            <input type="text" name="telepon" value="{{ $pengaturan?->telepon ?? '' }}"
                                class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Logo Perusahaan</label>
                            @if ($pengaturan?->logo)
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo"
                                        class="h-20 object-contain">
                                </div>
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maksimal 2MB. Logo akan muncul di
                                invoice.</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Alamat</label>
                            <textarea name="alamat" rows="3" class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">{{ $pengaturan?->alamat ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-gray-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-gray-700">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>

            <!-- PAKET WIFI -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class='bx bx-wifi text-gray-600'></i> Paket WiFi
                    </h3>
                    <button data-modal-target="modalTambahPaket" data-modal-toggle="modalTambahPaket"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Tambah Paket</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama Paket</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                                <th class="px-4 py-3 text-center">Kecepatan</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($paketWifi as $p)
                            <tr>
                                <td class="px-4 py-3">{{ $p->nama }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">{{ $p->kecepatan ? $p->kecepatan . ' Mbps' : '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button data-modal-target="editPaket-{{ $p->id }}" data-modal-toggle="editPaket-{{ $p->id }}"
                                        class="text-amber-600 hover:text-amber-800 mr-2"><i class='bx bx-edit'></i></button>
                                    <form action="{{ route('pengaturan.paket.destroy', $p->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin hapus paket ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"><i class='bx bx-trash'></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-gray-400">Belum ada paket WiFi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PENANGGUNG JAWAB WIFI -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class='bx bx-user-pin text-gray-600'></i> Penanggung Jawab WiFi per Wilayah
                    </h3>
                    <button data-modal-target="modalTambahPJ" data-modal-toggle="modalTambahPJ"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Tambah</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Telepon</th>
                                <th class="px-4 py-3 text-left">Wilayah</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($penanggungJawab as $pj)
                            <tr>
                                <td class="px-4 py-3">{{ $pj->nama }}</td>
                                <td class="px-4 py-3">{{ $pj->telepon ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $pj->wilayah ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button data-modal-target="editPJ-{{ $pj->id }}" data-modal-toggle="editPJ-{{ $pj->id }}"
                                        class="text-amber-600 hover:text-amber-800 mr-2"><i class='bx bx-edit'></i></button>
                                    <form action="{{ route('pengaturan.penanggung-jawab.destroy', $pj->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin hapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"><i class='bx bx-trash'></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-gray-400">Belum ada penanggung jawab</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Log Aktivitas -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    <i class='bx bx-history text-gray-600'></i> Log Aktivitas
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">User</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                                <th class="px-4 py-3 text-left">Model</th>
                                <th class="px-4 py-3 text-left">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($logs as $log)
                            <tr>
                                <td class="px-4 py-3">{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3">{{ $log->user->nama_lengkap }}</td>
                                <td class="px-4 py-3">
                                    @if($log->aksi == 'create')
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Tambah</span>
                                    @elseif($log->aksi == 'update')
                                        <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs">Ubah</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Hapus</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $log->model }}</td>
                                <td class="px-4 py-3">{{ $log->deskripsi }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-gray-400">Belum ada aktivitas</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $logs->links() }}</div>
            </div>
        @endif
    </div>

    {{-- MODAL TAMBAH PAKET --}}
    <div id="modalTambahPaket" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Tambah Paket WiFi</h3>
                <button data-modal-hide="modalTambahPaket" class="text-gray-400 text-xl">&times;</button>
            </div>
            <form action="{{ route('pengaturan.paket.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-sm font-medium">Nama Paket</label>
                    <input type="text" name="nama" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Harga (Rp)</label>
                    <input type="number" name="harga" required min="0" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Kecepatan (Mbps)</label>
                    <input type="number" name="kecepatan" min="0" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full border rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-hide="modalTambahPaket" class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH PENANGGUNG JAWAB --}}
    <div id="modalTambahPJ" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Tambah Penanggung Jawab</h3>
                <button data-modal-hide="modalTambahPJ" class="text-gray-400 text-xl">&times;</button>
            </div>
            <form action="{{ route('pengaturan.penanggung-jawab.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" name="nama" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Telepon</label>
                    <input type="text" name="telepon" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Wilayah</label>
                    <input type="text" name="wilayah" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-hide="modalTambahPJ" class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT PAKET --}}
    @foreach($paketWifi as $p)
    <div id="editPaket-{{ $p->id }}" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Edit Paket WiFi</h3>
                <button data-modal-hide="editPaket-{{ $p->id }}" class="text-gray-400 text-xl">&times;</button>
            </div>
            <form action="{{ route('pengaturan.paket.update', $p->id) }}" method="POST" class="space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-medium">Nama Paket</label>
                    <input type="text" name="nama" value="{{ $p->nama }}" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ $p->harga }}" required min="0" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Kecepatan (Mbps)</label>
                    <input type="number" name="kecepatan" value="{{ $p->kecepatan }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">{{ $p->deskripsi }}</textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-hide="editPaket-{{ $p->id }}" class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    {{-- MODAL EDIT PENANGGUNG JAWAB --}}
    @foreach($penanggungJawab as $pj)
    <div id="editPJ-{{ $pj->id }}" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Edit Penanggung Jawab</h3>
                <button data-modal-hide="editPJ-{{ $pj->id }}" class="text-gray-400 text-xl">&times;</button>
            </div>
            <form action="{{ route('pengaturan.penanggung-jawab.update', $pj->id) }}" method="POST" class="space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" name="nama" value="{{ $pj->nama }}" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Telepon</label>
                    <input type="text" name="telepon" value="{{ $pj->telepon }}" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium">Wilayah</label>
                    <input type="text" name="wilayah" value="{{ $pj->wilayah }}" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-hide="editPJ-{{ $pj->id }}" class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
@endsection
