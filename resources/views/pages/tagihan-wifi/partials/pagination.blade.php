@if ($tagihan->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700">
        {{ $tagihan->withQueryString()->links() }}
    </div>
@endif