@forelse($invoices as $invoice)
    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30 transition duration-150">
        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 font-medium">
            {{ $invoices->firstItem() + $loop->index }}
        </td>
        <td class="px-4 py-3">
            <a href="{{ route('invoice.show', $invoice->id) }}"
                class="font-mono text-xs font-semibold text-gray-600 dark:text-blue-400 hover:underline">
                {{ $invoice->kode_invoice }}
            </a>
            <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ $invoice->tanggalJ }}
            </div>
        </td>
        <td class="px-4 py-3">
            <p class="font-medium text-gray-800 dark:text-white">{{ $invoice->pelanggan->nama }}</p>
            <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                <i class='bx {{ $invoice->pelanggan->tipe == 'individu' ? 'bx-user' : ($invoice->pelanggan->tipe == 'instansi' ? 'bx-building' : 'bx-store') }} text-xs'></i>
                {{ ucfirst($invoice->pelanggan->tipe) }}
            </span>
        </td>
        <td class="px-4 py-3 text-center hidden sm:table-cell text-gray-600 dark:text-gray-400">
            {{ $invoice->tanggalJ }}
        </td>
        <td class="px-4 py-3 text-center hidden md:table-cell">
            @if ($invoice->jatuh_tempo)
                @php
                    $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($invoice->jatuh_tempo), false);
                @endphp
                <span class="{{ $daysLeft <= 3 && $invoice->status !== 'lunas' ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                    {{ $invoice->jatuh_tempoJ }}
                    @if ($daysLeft <= 3 && $daysLeft >= 0 && $invoice->status !== 'lunas')
                        <span class="block text-xs text-red-500">({{ $daysLeft }} hari lagi)</span>
                    @endif
                </span>
            @else
                <span class="text-gray-400">-</span>
            @endif
        </td>
        <td class="px-4 py-3 text-right font-semibold text-gray-800 dark:text-white">
            Rp {{ number_format($invoice->total, 0, ',', '.') }}
        </td>
        <td class="px-4 py-3 text-right hidden lg:table-cell text-green-600 dark:text-green-400">
            Rp {{ number_format($invoice->dibayar, 0, ',', '.') }}
        </td>
        <td class="px-4 py-3 text-right font-semibold {{ $invoice->sisa > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
            Rp {{ number_format($invoice->sisa, 0, ',', '.') }}
        </td>
        <td class="px-4 py-3 text-center hidden xl:table-cell">
            @if ($invoice->status === 'lunas')
                <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-full text-xs font-medium">
                    <i class='bx bx-check-circle text-xs'></i> Lunas
                </span>
            @else
                <span class="inline-flex items-center gap-1 bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-400 px-2.5 py-1 rounded-full text-xs font-medium">
                    <i class='bx bx-time text-xs'></i> Cicilan
                </span>
            @endif
        </td>
        <td class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                <a href="{{ route('invoice.show', $invoice->id) }}"
                    class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-blue-400 hover:bg-gray-200 dark:hover:bg-gray-900/50 rounded-lg transition"
                    title="Detail">
                    <i class='bx bx-show text-sm'></i>
                </a>
                @if ($invoice->status !== 'lunas')
                    <a href="{{ route('invoice.bayar', $invoice->id) }}"
                        class="inline-flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 rounded-lg transition"
                        title="Bayar">
                        <i class='bx bx-money text-sm'></i>
                    </a>
                    <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" class="inline" id="delete-form-{{ $invoice->id }}">
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDeleteInvoice('delete-form-{{ $invoice->id }}')"
                            class="inline-flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition"
                            title="Hapus">
                            <i class='bx bx-trash text-sm'></i>
                        </button>
                    </form>
                @endif
                <a href="{{ route('invoice.cetak', $invoice->id) }}" target="_blank"
                    class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 hover:bg-purple-200 dark:hover:bg-purple-900/50 rounded-lg transition"
                    title="Cetak PDF">
                    <i class='bx bx-printer text-sm'></i>
                </a>
                @if (auth()->user()->role == 'owner')
                    <button type="button" onclick="showModalPajak('{{ $invoice->kode_invoice }}', {{ $invoice->total }})"
                        class="inline-flex items-center justify-center w-8 h-8 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900/50 rounded-lg transition"
                        title="Info Pajak">
                        <i class='bx bx-calculator text-sm'></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
            <i class='bx bx-receipt text-4xl mb-2 block'></i>
            <p>Belum ada tagihan</p>
            <a href="{{ route('invoice.create') }}"
                class="inline-block mt-3 text-gray-600 dark:text-blue-400 text-sm hover:underline">
                Buat tagihan pertama →
            </a>
        </td>
    </tr>
@endforelse