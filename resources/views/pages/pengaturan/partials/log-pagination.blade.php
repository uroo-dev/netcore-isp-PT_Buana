@if ($logs->hasPages())
    <div class="flex items-center justify-between flex-wrap gap-2" id="logPagination">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }}
        </p>
        {{ $logs->links() }}
    </div>
@else
    <div class="flex items-center justify-between flex-wrap gap-2">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Total {{ $logs->total() }} data
        </p>
    </div>
@endif