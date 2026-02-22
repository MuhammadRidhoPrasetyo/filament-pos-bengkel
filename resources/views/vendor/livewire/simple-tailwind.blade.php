@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-3">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-400 bg-white border-2 border-gray-200 cursor-default rounded-lg shadow-sm dark:text-gray-400 dark:bg-gradient-to-br dark:from-neutral-800 dark:to-neutral-900 dark:border-neutral-700 dark:shadow-lg dark:shadow-black/30">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {!! __('previous') !!}
                </span>
            @else
                @if(method_exists($paginator,'getCursorName'))
                    <button type="button" dusk="previousPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}" wire:click="setPage('{{$paginator->previousCursor()->encode()}}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-200 rounded-lg shadow-sm transition-all duration-200 hover:border-sky-400 hover:bg-sky-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-0 focus:border-sky-400 dark:bg-gradient-to-br dark:from-neutral-800 dark:to-neutral-900 dark:border-neutral-600 dark:text-sky-300 dark:shadow-lg dark:shadow-black/30 dark:hover:border-sky-500 dark:hover:from-sky-900/40 dark:hover:to-neutral-900 dark:hover:shadow-xl dark:hover:shadow-sky-500/20 dark:focus:ring-sky-400 dark:focus:border-sky-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        {!! __('previous') !!}
                    </button>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-200 rounded-lg shadow-sm transition-all duration-200 hover:border-sky-400 hover:bg-sky-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-0 focus:border-sky-400 dark:bg-gradient-to-br dark:from-neutral-800 dark:to-neutral-900 dark:border-neutral-600 dark:text-sky-300 dark:shadow-lg dark:shadow-black/30 dark:hover:border-sky-500 dark:hover:from-sky-900/40 dark:hover:to-neutral-900 dark:hover:shadow-xl dark:hover:shadow-sky-500/20 dark:focus:ring-sky-400 dark:focus:border-sky-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        {!! __('previous') !!}
                    </button>
                @endif
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                @if(method_exists($paginator,'getCursorName'))
                    <button type="button" dusk="nextPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}" wire:click="setPage('{{$paginator->nextCursor()->encode()}}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-200 rounded-lg shadow-sm transition-all duration-200 hover:border-sky-400 hover:bg-sky-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-0 focus:border-sky-400 dark:bg-gradient-to-br dark:from-neutral-800 dark:to-neutral-900 dark:border-neutral-600 dark:text-sky-300 dark:shadow-lg dark:shadow-black/30 dark:hover:border-sky-500 dark:hover:from-sky-900/40 dark:hover:to-neutral-900 dark:hover:shadow-xl dark:hover:shadow-sky-500/20 dark:focus:ring-sky-400 dark:focus:border-sky-400">
                        {!! __('next') !!}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-200 rounded-lg shadow-sm transition-all duration-200 hover:border-sky-400 hover:bg-sky-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-0 focus:border-sky-400 dark:bg-gradient-to-br dark:from-neutral-800 dark:to-neutral-900 dark:border-neutral-600 dark:text-sky-300 dark:shadow-lg dark:shadow-black/30 dark:hover:border-sky-500 dark:hover:from-sky-900/40 dark:hover:to-neutral-900 dark:hover:shadow-xl dark:hover:shadow-sky-500/20 dark:focus:ring-sky-400 dark:focus:border-sky-400">
                        {!! __('next') !!}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @endif
            @else
                <span class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-400 bg-white border-2 border-gray-200 cursor-default rounded-lg shadow-sm dark:text-gray-400 dark:bg-gradient-to-br dark:from-neutral-800 dark:to-neutral-900 dark:border-neutral-700 dark:shadow-lg dark:shadow-black/30">
                    {!! __('next') !!}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </nav>
    @endif
</div>
