@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-1 text-sm" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 rounded-md text-slate-300"><i class="fas fa-chevron-left"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 rounded-md text-slate-600 hover:bg-slate-100"><i class="fas fa-chevron-left"></i></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-2 text-slate-400">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 rounded-md bg-primary-900 text-white font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 rounded-md text-slate-600 hover:bg-slate-100">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 rounded-md text-slate-600 hover:bg-slate-100"><i class="fas fa-chevron-right"></i></a>
        @else
            <span class="px-3 py-2 rounded-md text-slate-300"><i class="fas fa-chevron-right"></i></span>
        @endif
    </nav>
@endif
