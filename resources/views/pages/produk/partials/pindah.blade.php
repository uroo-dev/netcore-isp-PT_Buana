@foreach ($produk as $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition">
        <td class="px-3 py-2">
            <input type="checkbox" name="produk_ids[]" value="{{ $item->id }}"
                class="produk-checkbox rounded">
        </td>
        <td class="px-3 py-2 text-gray-800 dark:text-white">{{ $item->nama }}</td>
        <td
            class="px-3 py-2 text-center font-medium {{ $item->stok == 0 ? 'text-red-600' : 'text-gray-800 dark:text-white' }}">
            {{ $item->stok }}</td>
        <td class="px-3 py-2 text-center">
            <input type="number" name="jumlah[{{ $item->id }}]" min="0"
                max="{{ $item->stok }}" value="0"
                class="w-20 border border-gray-200 dark:border-dark-700 rounded-lg px-2 py-1 text-xs text-center bg-white dark:bg-dark-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500"
                {{ $item->stok == 0 ? 'disabled' : '' }}>
        </td>
    </tr>
@endforeach