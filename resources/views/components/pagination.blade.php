@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6 w-full">
        <div class="text-sm text-brand-text-secondary">
            Showing <span class="font-medium text-brand-text-primary">{{ $paginator->firstItem() }}</span> to <span class="font-medium text-brand-text-primary">{{ $paginator->lastItem() }}</span> of <span class="font-medium text-brand-text-primary">{{ $paginator->total() }}</span> results
        </div>

        <nav class="flex flex-wrap justify-center items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-full text-brand-text-secondary opacity-50 cursor-not-allowed">
                    <i class="ph ph-caret-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-brand-text-primary hover:bg-white/10 transition-colors">
                    <i class="ph ph-caret-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-8 h-8 flex items-center justify-center text-brand-text-secondary tracking-widest">...</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-brand-accent-primary text-white font-medium shadow-lg shadow-brand-accent-primary/20">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-full text-brand-text-secondary hover:text-brand-text-primary hover:bg-white/10 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-brand-text-primary hover:bg-white/10 transition-colors">
                    <i class="ph ph-caret-right"></i>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center rounded-full text-brand-text-secondary opacity-50 cursor-not-allowed">
                    <i class="ph ph-caret-right"></i>
                </span>
            @endif
        </nav>
    </div>
@endif
