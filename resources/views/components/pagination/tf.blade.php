@if ($paginator->hasPages())
    <ul class="tf-pagination-wrap tf-pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true" aria-label="Previous">
                <span class="pagination-link" aria-hidden="true">
                    <span class="icon icon-arrow-left"></span>
                </span>
            </li>
        @else
            <li>
                <a class="pagination-link animate-hover-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                    <span class="icon icon-arrow-left"></span>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span class="pagination-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page"><span class="pagination-link">{{ $page }}</span></li>
                    @else
                        <li><a class="pagination-link animate-hover-btn" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a class="pagination-link animate-hover-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                    <span class="icon icon-arrow-right"></span>
                </a>
            </li>
        @else
            <li class="disabled" aria-disabled="true" aria-label="Next">
                <span class="pagination-link" aria-hidden="true">
                    <span class="icon icon-arrow-right"></span>
                </span>
            </li>
        @endif
    </ul>
@endif
