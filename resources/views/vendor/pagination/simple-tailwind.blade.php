@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{!! __('Pagination Navigation') !!}" class="flex items-center gap-x-2">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}"
                class="min-h-10 min-w-10 px-3 py-2 inline-flex items-center gap-x-1.5 text-sm
                         rounded-lg border border-transparent
                         text-gray-400 dark:text-neutral-500
                         bg-gray-50/70 dark:bg-white/5
                         cursor-not-allowed select-none">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span>{{ __('pagination.previous') }}</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
                class="min-h-10 min-w-10 px-3 py-2 inline-flex items-center gap-x-1.5 text-sm rounded-lg
                      border border-amber-200/60 dark:border-amber-500/30
                      text-amber-700 dark:text-amber-300
                      bg-amber-50/60 dark:bg-amber-500/10
                      hover:bg-amber-100 dark:hover:bg-amber-500/20
                      hover:border-amber-300 dark:hover:border-amber-400
                      focus:outline-none focus:ring-2 focus:ring-amber-400/60 dark:focus:ring-amber-500/50
                      transition shadow-sm">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span>{{ __('pagination.previous') }}</span>
            </a>
        @endif

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
                class="min-h-10 min-w-10 px-3 py-2 inline-flex items-center gap-x-1.5 text-sm rounded-lg
                      border border-amber-200/60 dark:border-amber-500/30
                      text-amber-700 dark:text-amber-300
                      bg-amber-50/60 dark:bg-amber-500/10
                      hover:bg-amber-100 dark:hover:bg-amber-500/20
                      hover:border-amber-300 dark:hover:border-amber-400
                      focus:outline-none focus:ring-2 focus:ring-amber-400/60 dark:focus:ring-amber-500/50
                      transition shadow-sm">
                <span>{{ __('pagination.next') }}</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        @else
            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                class="min-h-10 min-w-10 px-3 py-2 inline-flex items-center gap-x-1.5 text-sm
                         rounded-lg border border-transparent
                         text-gray-400 dark:text-neutral-500
                         bg-gray-50/70 dark:bg-white/5
                         cursor-not-allowed select-none">
                <span>{{ __('pagination.next') }}</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </span>
        @endif
    </nav>
@endif
