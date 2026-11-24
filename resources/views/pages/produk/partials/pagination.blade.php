@if ($produk->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-dark-700">
        {{ $produk->withQueryString()->links() }}
    </div>
@endif