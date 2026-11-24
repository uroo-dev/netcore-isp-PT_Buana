<div class="flex items-center justify-between flex-wrap gap-2">
    <p class="text-xs text-gray-500 dark:text-gray-400">
        Menampilkan {{ $invoices->firstItem() ?? 0 }}-{{ $invoices->lastItem() ?? 0 }} dari {{ $invoices->total() }}
    </p>
    @if ($invoices->hasPages())
        {{ $invoices->withQueryString()->links() }}
    @endif
</div>