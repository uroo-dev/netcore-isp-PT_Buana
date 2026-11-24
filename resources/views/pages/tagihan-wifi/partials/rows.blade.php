@forelse($tagihan as $inv)
    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
        <td class="px-5 py-3">
            <a href="{{ route('invoice.show', $inv->id) }}" class="font-medium text-gray-600 dark:text-blue-400 hover:underline">
                {{ $inv->kode_invoice }}
            </a>
        </td>
        <td class="px-5 py-3">
            <div class="font-medium text-gray-800 dark:text-white">{{ $inv->pelanggan?->nama ?? '-' }}</div>
        </td>
        <td class="px-5 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
            {{ $inv->tanggal ? \Carbon\Carbon::parse($inv->tanggal)->translatedFormat('d/m/Y') : '-' }}
        </td>
        <td class="px-5 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
            {{ $inv->jatuh_tempo ? \Carbon\Carbon::parse($inv->jatuh_tempo)->translatedFormat('d/m/Y') : '-' }}
        </td>
        <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-white">
            Rp {{ number_format($inv->total, 0, ',', '.') }}
        </td>
        <td class="px-5 py-3 text-right {{ $inv->sisa > 0 ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-green-600 dark:text-green-400' }}">
            Rp {{ number_format($inv->sisa, 0, ',', '.') }}
        </td>
        <td class="px-5 py-3 text-center">
            @if($inv->status === 'lunas')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                    Lunas
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                    Piutang
                </span>
            @endif
        </td>
        <td class="px-5 py-3">
            <div class="flex items-center justify-center gap-2">
                <a href="{{ route('invoice.show', $inv->id) }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all duration-200"
                    title="Detail">
                    <i class='bx bx-show text-lg'></i>
                </a>
                @if(auth()->user()->role == 'owner')
                <button type="button"
                    onclick="showModalPajak('{{ $inv->kode_invoice }}', {{ $inv->total }})"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all duration-200"
                    title="Info Pajak">
                    <i class='bx bx-calculator text-lg'></i>
                </button>
                @endif
                @if($inv->status !== 'lunas')
                <form action="{{ route('tagihan-wifi.destroy', $inv->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="button" onclick="confirmHapus(this)"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-all duration-200"
                        title="Hapus">
                        <i class='bx bx-trash-alt text-lg'></i>
                    </button>
                </form>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-12">
            <i class='bx bx-wifi-off text-5xl text-gray-300 dark:text-gray-600'></i>
            <p class="text-gray-400 dark:text-gray-500 mt-3">Belum ada tagihan WiFi</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Klik "Buat Tagihan WiFi" untuk membuat tagihan baru</p>
        </td>
    </tr>
@endforelse