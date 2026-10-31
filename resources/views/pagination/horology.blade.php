@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation"
        class="veltra-pagination mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">

        <!-- Results Summary on Page -->
        <div class="veltra-pagination-info text-xs text-[#8e969d] font-['Inter',sans-serif] order-2 sm:order-1">
            Showing <span class="font-semibold text-white veltra-pagination-highlight">{{ $paginator->firstItem() }}</span> to <span class="font-semibold text-white veltra-pagination-highlight">{{ $paginator->lastItem() }}</span> of <span class="font-semibold text-white veltra-pagination-highlight">{{ $paginator->total() }}</span> watches
        </div>

        <!-- Page Navigation Controls -->
        <div class="veltra-pagination-controls flex items-center gap-1.5 sm:gap-2 order-1 sm:order-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="veltra-pagination-btn veltra-pagination-disabled inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-medium text-white/30 bg-white/[0.03] border border-white/5 cursor-not-allowed select-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span class="hidden sm:inline">Prev</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    class="veltra-pagination-btn inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-medium text-[#d59a7a] bg-[#11171d] border border-white/10 hover:border-[#cb8665] hover:text-white hover:bg-[#1a232c] transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span class="hidden sm:inline">Prev</span>
                </a>
            @endif

            {{-- Page Number Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="veltra-pagination-ellipsis px-2 py-1 text-xs text-white/40 select-none">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="veltra-pagination-page veltra-pagination-active min-w-[34px] sm:min-w-[38px] h-[34px] sm:h-[38px] inline-flex items-center justify-center px-2.5 rounded-lg text-xs font-bold text-white bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] shadow-[0_2px_12px_rgba(203,134,101,0.45)] border border-[#f5be9f]/40 select-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="veltra-pagination-page min-w-[34px] sm:min-w-[38px] h-[34px] sm:h-[38px] inline-flex items-center justify-center px-2.5 rounded-lg text-xs font-medium text-[#8e969d] bg-[#11171d] border border-white/10 hover:border-[#cb8665] hover:text-white hover:bg-[#1a232c] transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    class="veltra-pagination-btn inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-medium text-[#d59a7a] bg-[#11171d] border border-white/10 hover:border-[#cb8665] hover:text-white hover:bg-[#1a232c] transition-all shadow-sm">
                    <span class="hidden sm:inline">Next</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            @else
                <span class="veltra-pagination-btn veltra-pagination-disabled inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-medium text-white/30 bg-white/[0.03] border border-white/5 cursor-not-allowed select-none">
                    <span class="hidden sm:inline">Next</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </span>
            @endif
        </div>
    </nav>
@endif

