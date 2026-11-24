@if ($produkCabang->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-dark-700">
        {{ $produkCabang->withQueryString()->links() }}
    </div>
@endif