@extends('layouts.app')
@section('title', 'Backup Database')
@section('content')
    <div class="p-4 md:p-6 space-y-6 max-w-6xl mx-auto">
        <!-- Header -->
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-cog text-gray-600 dark:text-blue-400 text-2xl'></i>
                Pengaturan
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pengaturan akun dan perusahaan</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-xl bg-blue-100 dark:bg-blue-900/40">
                        <i class='bx bx-package text-xl text-blue-600 dark:text-blue-400'></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Backup</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $backupCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/40">
                        <i class='bx bxs-server text-xl text-green-600 dark:text-green-400'></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Penyimpanan Terpakai</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $diskUsedFormatted }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 p-5 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-xl bg-amber-100 dark:bg-amber-900/40">
                        <i class='bx bx-time text-xl text-amber-600 dark:text-amber-400'></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Backup Terakhir</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $lastBackup ? $lastBackup->created_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengaturan Backup -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-700">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-cog text-gray-600 dark:text-blue-400 text-xl'></i>
                    Pengaturan Backup
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('pengaturan.backup.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Email Notifikasi Backup
                            </label>
                            <div class="relative">
                                <i class='bx bx-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="email" name="backup_email" value="{{ $pengaturan?->backup_email ?? old('backup_email') }}"
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                                    placeholder="backup@example.com">
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 flex items-center gap-1">
                                <i class='bx bx-info-circle'></i>
                                Email untuk notifikasi hasil backup. Bisa berbeda dengan email perusahaan.
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Hapus Backup Otomatis (hari)
                            </label>
                            <div class="relative">
                                <i class='bx bx-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="number" name="backup_retention_days" value="{{ $pengaturan?->backup_retention_days ?? 30 }}"
                                    min="1" max="365"
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 flex items-center gap-1">
                                <i class='bx bx-info-circle'></i>
                                Backup otomatis akan dihapus setelah melewati jumlah hari ini.
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Jadwal Backup Otomatis
                            </label>
                            <div class="relative">
                                <i class='bx bx-time absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="time" name="backup_schedule_time" value="{{ $pengaturan?->backup_schedule_time ?? '02:00' }}"
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 flex items-center gap-1">
                                <i class='bx bx-info-circle'></i>
                                Backup otomatis berjalan setiap tanggal 1 pada jam yang ditentukan (WIB).
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100 dark:border-dark-700">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class='bx bx-save text-lg'></i>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Backup List -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class='bx bx-cloud-upload text-gray-600 dark:text-blue-400 text-xl'></i>
                    Riwayat Backup
                    <span class="text-xs bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 px-2 py-0.5 rounded-full">{{ $backups->total() }}</span>
                </h3>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('pengaturan.backup') }}" class="flex items-center gap-2">
                        <input type="date" name="date" value="{{ request('date') }}"
                            class="px-3 py-2 border border-gray-200 dark:border-dark-700 rounded-xl text-sm bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none transition-colors">
                        @if (request('date'))
                            <a href="{{ route('pengaturan.backup') }}"
                                class="px-3 py-2 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-600 dark:text-gray-400 rounded-xl text-sm transition">
                                <i class='bx bx-x'></i>
                            </a>
                        @endif
                        <button type="button" onclick="openBackupModal()"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class='bx bx-plus-circle text-lg'></i>
                            Backup Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 border-b dark:border-dark-700">
                        <tr>
                            <th class="px-4 py-3 text-left">File</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Ukuran</th>
                            <th class="px-4 py-3 text-left">Tanggal Backup</th>
                            <th class="px-4 py-3 text-center hidden md:table-cell">Retensi (hari)</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-dark-700">
                        @forelse($backups as $backup)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-file text-gray-400'></i>
                                        <span class="font-medium text-gray-800 dark:text-white truncate max-w-[200px] block">{{ $backup->filename }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 hidden md:table-cell text-gray-600 dark:text-gray-400">
                                    @php
                                        $u = ['B', 'KB', 'MB', 'GB'];
                                        $b = max($backup->size ?? 0, 0);
                                        $p = floor(($b ? log($b) : 0) / log(1024));
                                        $p = min($p, count($u) - 1);
                                        echo $b ? round($b / pow(1024, $p), 2) . ' ' . $u[$p] : '-';
                                    @endphp
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    <div>{{ $backup->backup_date ? \Carbon\Carbon::parse($backup->backup_date)->format('d M Y') : $backup->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $backup->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-4 py-3 text-center hidden md:table-cell text-gray-600 dark:text-gray-400">
                                    {{ $backup->retention_days }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($backup->status == 'success')
                                        <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full text-xs font-medium">
                                            <i class='bx bx-check-circle'></i> Sukses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2.5 py-1 rounded-full text-xs font-medium">
                                            <i class='bx bx-x-circle'></i> Gagal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('pengaturan.backup.download', $backup->id) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-900/60 transition">
                                            <i class='bx bx-download'></i>
                                        </a>
                                        <form action="{{ route('pengaturan.backup.destroy', $backup->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus backup {{ $backup->filename }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 rounded-lg text-xs font-medium hover:bg-red-200 dark:hover:bg-red-900/60 transition">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <i class='bx bx-cloud-upload text-4xl mb-2 block'></i>
                                    <p>Belum ada backup</p>
                                    <p class="text-xs mt-1">Klik "Backup Sekarang" untuk membuat backup pertama</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($backups->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-dark-700">
                    {{ $backups->withQueryString()->links() }}
                </div>
            @endif
        </div>

        <!-- Informasi -->
        <div class="bg-gray-50 dark:bg-gray-900/20 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class='bx bx-info-circle text-gray-600 dark:text-blue-400 text-xl mt-0.5'></i>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-blue-300">Informasi Backup</p>
                    <p class="text-xs text-gray-700 dark:text-blue-400 mt-1">
                        Backup menyimpan seluruh database. File backup disimpan di <code class="bg-gray-200 dark:bg-gray-800 px-1 rounded">storage/app/backups</code>.
                        Backup otomatis berjalan setiap tanggal 1 pukul <strong>{{ $pengaturan?->backup_schedule_time ?? '02:00' }} WIB</strong>. Notifikasi dikirim ke email backup yang ditentukan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Backup -->
    <div id="backupModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-700 flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white">Backup Database</h3>
                <button type="button" onclick="closeBackupModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('pengaturan.backup.create') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Backup</label>
                    <div class="relative">
                        <i class='bx bx-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                        <input type="date" name="backup_date" value="{{ old('backup_date', date('Y-m-d')) }}"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Simpan Backup Selama</label>
                    <div class="relative">
                        <i class='bx bx-time absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                        <select name="retention_days"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors appearance-none">
                            <option value="7">7 Hari</option>
                            <option value="14">14 Hari</option>
                            <option value="30" selected>30 Hari</option>
                            <option value="60">60 Hari</option>
                            <option value="90">90 Hari</option>
                            <option value="0">Selamanya</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="closeBackupModal()"
                        class="px-4 py-2.5 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class='bx bx-cloud-upload'></i>
                        Jalankan Backup
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openBackupModal() {
        document.getElementById('backupModal').classList.remove('hidden');
        document.getElementById('backupModal').classList.add('flex');
    }
    function closeBackupModal() {
        document.getElementById('backupModal').classList.add('hidden');
        document.getElementById('backupModal').classList.remove('flex');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('backupModal').classList.contains('hidden')) {
            closeBackupModal();
        }
    });
    document.getElementById('backupModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeBackupModal();
    });
</script>
@endpush
