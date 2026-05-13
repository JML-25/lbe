@if ($paginator->hasPages())
    <nav>
        @if ($paginator->onFirstPage())
            <span>&laquo;</span>
        @else
            <a wire:navigate href="{{ $paginator->previousPageUrl() }}">&laquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page">{{ $page }}</span>
                    @else
                        <a wire:navigate href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a wire:navigate href="{{ $paginator->nextPageUrl() }}">&raquo;</a>
        @else
            <span>&raquo;</span>
        @endif
    </nav>
@endif