@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">

        {{-- Info --}}
        <p class="text-sm text-gray-500">
            Menampilkan <span class="font-medium text-gray-700">{{ $paginator->firstItem() }}</span>
            sampai <span class="font-medium text-gray-700">{{ $paginator->lastItem() }}</span>
            dari <span class="font-medium text-gray-700">{{ $paginator->total() }}</span> data
        </p>

        {{-- Pagination --}}
        <nav class="flex items-center gap-1">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-full cursor-not-allowed">
                    <i class='bx bx-chevron-left'></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition">
                    <i class='bx bx-chevron-left'></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-gray-400">...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3.5 py-2 text-sm font-medium text-white bg-gray-600 rounded-full shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3.5 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition">
                    <i class='bx bx-chevron-right'></i>
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-full cursor-not-allowed">
                    <i class='bx bx-chevron-right'></i>
                </span>
            @endif

        </nav>
    </div>
@endif
