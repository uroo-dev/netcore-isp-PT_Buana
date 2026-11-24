@forelse($logs as $log)
    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
        <td class="px-4 py-3 whitespace-nowrap text-gray-600 dark:text-gray-400">
            <div class="flex items-center gap-1">
                <i class='bx bx-calendar text-xs text-gray-400'></i>
                {{ $log->created_at->format('d M Y H:i') }}
            </div>
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-900/30 flex items-center justify-center">
                    <span class="text-xs font-medium text-gray-600 dark:text-blue-400">{{ $log->user ? substr($log->user->nama_lengkap, 0, 1) : '?' }}</span>
                </div>
                <span class="font-medium text-gray-800 dark:text-white">{{ $log->user?->nama_lengkap ?? 'Sistem' }}</span>
            </div>
        </td>
        <td class="px-4 py-3 text-center">
            @php
                $badge = [
                    'create' => ['bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400', 'bx-plus-circle', 'Tambah'],
                    'update' => ['bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400', 'bx-edit-alt', 'Ubah'],
                    'delete' => ['bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400', 'bx-trash', 'Hapus'],
                    'login' => ['bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400', 'bx-log-in-circle', 'Login'],
                    'logout' => ['bg-gray-100 dark:bg-gray-900/40 text-gray-600 dark:text-gray-400', 'bx-log-out-circle', 'Logout'],
                    'failed' => ['bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400', 'bx-lock-alt', 'Gagal'],
                    'access' => ['bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400', 'bx-link', 'Akses'],
                    'error' => ['bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400', 'bx-error-circle', 'Error'],
                ];
                [$cls, $icon, $label] = $badge[$log->aksi] ?? ['bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400', 'bx-dots-horizontal-rounded', $log->aksi];
            @endphp
            <span class="inline-flex items-center gap-1 {{ $cls }} px-2.5 py-1 rounded-full text-xs font-medium">
                <i class='bx {{ $icon }} text-xs'></i> {{ $label }}
            </span>
        </td>
        <td class="px-4 py-3 hidden md:table-cell">
            <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded-lg text-xs">
                <i class='bx bx-data text-xs'></i> {{ $log->model }}
            </span>
        </td>
        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs">
            <div class="truncate">{{ $log->deskripsi }}</div>
            <div class="md:hidden text-xs text-gray-500 dark:text-gray-500 mt-0.5">
                <i class='bx bx-data text-xs'></i> {{ $log->model }}
            </div>
        </td>
    </tr>
@empty
    <tr id="emptyRow">
        <td colspan="5" class="text-center py-12 text-gray-500 dark:text-gray-400">
            <i class='bx bx-history text-4xl mb-2 block'></i>
            <p>Belum ada data sesuai filter</p>
        </td>
    </tr>
@endforelse