<div>
    <button type="button"
        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
        aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-full-screen-modal"
        data-hs-overlay="#hs-full-screen-modal">
        Full screen
    </button>

    <div id="hs-full-screen-modal" wire:ignore.self
        class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1" aria-labelledby="hs-full-screen-label">
        <div
            class="hs-overlay-open:mt-0 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-10 opacity-0 transition-all max-w-full max-h-full h-full">
            <div class="flex flex-col bg-white pointer-events-auto max-w-full max-h-full h-full dark:bg-neutral-800">
                <div
                    class="flex justify-between items-center py-3 px-4 border-b border-gray-200 dark:border-neutral-700">
                    <h3 id="hs-full-screen-label" class="font-bold text-gray-800 dark:text-white">
                        Modal title
                    </h3>
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600"
                        aria-label="Close" data-hs-overlay="#hs-full-screen-modal">
                        <span class="sr-only">Close</span>
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 p-4">
                        @foreach ($this->products as $product)
                            <div class="group flex flex-col">
                                <div class="relative">
                                    <div class="rounded-3xl bg-neutral-800 p-4">
                                        <div class="aspect-square overflow-hidden rounded-2xl">
                                            <img class="h-full w-full object-cover"
                                                src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=1200&auto=format&fit=crop"
                                                alt="Beija Flor" loading="lazy">
                                        </div>
                                    </div>

                                    <div class="pt-6">
                                        <h3 class="text-base md:text-lg font-semibold">{{ $product->product->name }}
                                        </h3>
                                        <p class="mt-2 text-sm md:text-base font-semibold">Stok :
                                            {{ $product->quantity }}</p>
                                    </div>

                                    <a href="#" class="absolute inset-0" aria-label="View Beija Flor"></a>
                                </div>

                                <div class="mt-6 text-sm">
                                    <div class="border-t border-neutral-700/70 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <span class="font-medium">Harga</span>
                                            <span
                                                class="text-right">{{ $product->productPrice->selling_price ?? '0' }}</span>
                                        </div>
                                    </div>
                                    <div class="border-t border-neutral-700/70 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <span class="font-medium">Keyword</span>
                                            <span class="text-right">{{ $product->product->keyword }}</span>
                                        </div>
                                    </div>
                                    <div class="border-t border-neutral-700/70 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <span class="font-medium">Bisa digunakan untuk</span>
                                            <span class="text-right">{{ $product->product->compatibility }}</span>
                                        </div>
                                    </div>
                                    <div class="border-t border-neutral-700/70 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <span class="font-medium">Tipe</span>
                                            <span class="text-right">{{ $product->product->type }}</span>
                                        </div>
                                    </div>
                                    <div class="border-t border-neutral-700/70 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <span class="font-medium">Ukuran</span>
                                            <span class="text-right">{{ $product->product->size }}</span>
                                        </div>
                                    </div>
                                    <div class="border-t border-neutral-700/70 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <span class="font-medium">SKU</span>
                                            <span class="text-right">{{ $product->product->sku }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <a href="#"
                                        class="w-full inline-flex justify-center items-center rounded-2xl px-4 py-3 text-sm font-medium whitespace-nowrap
                                  bg-yellow-400 text-black hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition">
                                        Masukkan Keranjang
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pastikan pagination juga ikut di area scroll --}}
                    <div class="p-4">
                        {{ $this->products->links() }}
                    </div>
                </div>
                <div
                    class="flex justify-end items-center gap-x-2 py-3 px-4 mt-auto border-t border-gray-200 dark:border-neutral-700">
                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                        data-hs-overlay="#hs-full-screen-modal">
                        Close
                    </button>
                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
