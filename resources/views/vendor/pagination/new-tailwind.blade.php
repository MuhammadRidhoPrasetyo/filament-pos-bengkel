<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-x-3 mt-4">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span
                    class="inline-flex items-center gap-x-1.5 px-4 py-2 text-sm font-medium rounded-lg
                           text-gray-400 dark:text-neutral-500
                           bg-gray-100 dark:bg-neutral-800
                           border border-transparent
                           cursor-not-allowed select-none">
                    <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    Previous
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
                    class="inline-flex items-center gap-x-1.5 px-4 py-2 text-sm font-medium rounded-lg
                           border border-amber-200 dark:border-amber-500/30
                           text-amber-700 dark:text-amber-300
                           bg-amber-50 dark:bg-amber-500/10
                           hover:bg-amber-100 dark:hover:bg-amber-500/20
                           focus:outline-none focus:ring-2 focus:ring-amber-400/60 dark:focus:ring-amber-500/50
                           disabled:opacity-50 disabled:cursor-not-allowed transition shadow-sm">
                    <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    Previous
                </button>
            @endif

            {{-- Next --}}
            @if ($paginator->onLastPage())
                <span
                    class="inline-flex items-center gap-x-1.5 px-4 py-2 text-sm font-medium rounded-lg
                           text-gray-400 dark:text-neutral-500
                           bg-gray-100 dark:bg-neutral-800
                           border border-transparent
                           cursor-not-allowed select-none">
                    Next
                    <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </span>
            @else
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                    class="inline-flex items-center gap-x-1.5 px-4 py-2 text-sm font-medium rounded-lg
                           border border-amber-200 dark:border-amber-500/30
                           text-amber-700 dark:text-amber-300
                           bg-amber-50 dark:bg-amber-500/10
                           hover:bg-amber-100 dark:hover:bg-amber-500/20
                           focus:outline-none focus:ring-2 focus:ring-amber-400/60 dark:focus:ring-amber-500/50
                           disabled:opacity-50 disabled:cursor-not-allowed transition shadow-sm">
                    Next
                    <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            @endif

        </nav>
    @endif
</div>
