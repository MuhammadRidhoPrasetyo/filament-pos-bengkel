<x-filament-panels::page>

    <x-filament::tabs>
        <x-filament::tabs.item :active="$activeTab === 'carts'" wire:click="$set('activeTab', 'carts')" icon="heroicon-m-shopping-cart">
            Keranjang
        </x-filament::tabs.item>
        <x-filament::tabs.item :active="$activeTab === 'products'" wire:click="$set('activeTab', 'products')"
            icon="heroicon-m-circle-stack">
            Produk
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div class="grid grid-cols-12 text-neutral-100 gap-8">
        @if ($activeTab === 'carts')
            <div class="col-span-12">
                <div
                    class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-gradient-to-r from-slate-50 to-sky-50 p-4
               shadow-sm dark:border-neutral-800 dark:from-neutral-900 dark:to-neutral-900/80">

                    {{-- Row atas: Judul + info kasir/toko --}}
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-500/10 text-sky-600
                           dark:bg-sky-500/20 dark:text-sky-300">
                                {{-- ikon dompet / kasir --}}
                                <x-heroicon-o-wallet class="h-5 w-5" />
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-neutral-100">
                                    Kasir POS
                                </h2>
                                <p class="text-[11px] text-gray-500 dark:text-neutral-400">
                                    Toko:
                                    <span class="font-medium">{{ $this->activeStore?->name ?? '-' }}</span>
                                    <span class="px-1 text-gray-400">•</span>
                                    Kasir:
                                    <span class="font-medium">{{ auth()->user()->name ?? '-' }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Info ringkas waktu (opsional) --}}
                        <div class="flex items-center gap-2 text-[11px] text-gray-500 dark:text-neutral-400">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-white/70 px-2 py-1 shadow-sm
                           dark:bg-neutral-800/80">
                                <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                <span> {{ now()->format('d M Y H:i') }} </span>
                            </span>
                        </div>
                    </div>

                    {{-- Row tengah: Customer + Payment method + Diskon --}}
                    <div class="grid gap-3 md:grid-cols-3">
                        {{-- Pilih Customer --}}
                        <div class="space-y-1">
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-neutral-300">
                                Customer
                            </label>
                            <select wire:model="customerId"
                                class="block w-full rounded-lg border-gray-200 bg-white px-2 py-1.5 text-xs text-gray-800 shadow-sm
                           focus:border-sky-400 focus:ring-0
                           dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-sky-500">
                                <option value="">Walk-in / Umum</option>
                                @foreach ($customerOptions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 dark:text-neutral-500">
                                Pilih customer terdaftar atau biarkan kosong untuk pelanggan umum.
                            </p>
                        </div>

                        {{-- Payment Method --}}
                        <div class="space-y-1">
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-neutral-300">
                                Metode Pembayaran
                            </label>
                            <select wire:model="paymentId"
                                class="block w-full rounded-lg border-gray-200 bg-white px-2 py-1.5 text-xs text-gray-800 shadow-sm
                           focus:border-sky-400 focus:ring-0
                           dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-sky-500">
                                <option value="">Pilih metode</option>
                                @foreach ($paymentOptions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 dark:text-neutral-500">
                                Contoh: Cash, QRIS, BCA, BRI, e-Wallet.
                            </p>
                        </div>

                        {{-- Diskon jenis & Diskon Universal --}}
                        <div class="space-y-3">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                {{-- Diskon jenis (P1, P2, ...) --}}
                                <div class="flex-1 space-y-1">
                                    <label class="block text-[11px] font-semibold text-gray-600 dark:text-neutral-300">
                                        Jenis Diskon Item
                                    </label>
                                    <select wire:model.change="selectedDiscountTypeId"
                                        class="block w-full rounded-lg border-gray-200 bg-white px-2 py-1.5 text-xs text-gray-800 shadow-sm
                                   focus:border-sky-400 focus:ring-0
                                   dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-sky-500">
                                        <option value="">Tanpa Diskon</option>
                                        @foreach ($discountTypeOptions as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-gray-400 dark:text-neutral-500">
                                        Diskon per-barang.
                                    </p>
                                </div>

                                {{-- Diskon universal --}}
                                <div class="flex-1 space-y-1">
                                    <label class="block text-[11px] font-semibold text-gray-600 dark:text-neutral-300">
                                        Diskon Universal
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <select wire:model="universalDiscountMode"
                                            class="block w-24 rounded-lg border-gray-200 bg-white px-2 py-1.5 text-[11px] text-gray-800 shadow-sm
                                       focus:border-sky-400 focus:ring-0
                                       dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-sky-500">
                                            <option value="">-</option>
                                            <option value="percent">%</option>
                                            <option value="amount">Rp</option>
                                        </select>
                                        <input type="number" step="0.01" wire:model.lazy="universalDiscountValue"
                                            class="block w-full rounded-lg border-gray-200 bg-white px-2 py-1.5 text-xs text-right text-gray-800 shadow-sm
                                       focus:border-sky-400 focus:ring-0
                                       dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-sky-500"
                                            placeholder="0" />
                                    </div>
                                    <p class="text-[10px] text-gray-400 dark:text-neutral-500">
                                        Berlaku ke total setelah diskon item.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 flex flex-col md:flex-col lg:flex-row gap-8">
                <div class="grid grid-cols-1 min-w-full gap-8">
                    <!-- Table Section -->
                    <div class="">
                        <!-- Card -->
                        <div class="flex flex-col">
                            <div class="-m-1.5 overflow-x-auto">
                                <div class="p-1.5 min-w-full inline-block align-middle">
                                    <div
                                        class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                                        <!-- Header -->
                                        {{-- <div
                                        class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                                        <!-- Input -->
                                        <div class="sm:col-span-1">
                                            <label for="hs-as-table-product-review-search"
                                                class="sr-only">Search</label>
                                            <div class="relative">
                                                <input type="text" id="hs-as-table-product-review-search"
                                                    name="hs-as-table-product-review-search"
                                                    class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                                    placeholder="Search">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                    <svg class="shrink-0 size-4 text-gray-400 dark:text-neutral-500"
                                                        xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <div class="sm:col-span-2 md:grow">
                                            <div class="flex justify-end gap-x-2">
                                                <div
                                                    class="hs-dropdown [--placement:bottom-right] relative inline-block">
                                                    <button id="hs-as-table-table-export-dropdown" type="button"
                                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                                                        aria-haspopup="menu" aria-expanded="false"
                                                        aria-label="Dropdown">
                                                        <svg class="shrink-0 size-3.5"
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                            <polyline points="7 10 12 15 17 10" />
                                                            <line x1="12" x2="12" y1="15"
                                                                y2="3" />
                                                        </svg>
                                                        Export
                                                    </button>
                                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-48 z-10 bg-white shadow-md rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700"
                                                        role="menu" aria-orientation="vertical"
                                                        aria-labelledby="hs-as-table-table-export-dropdown">
                                                        <div class="py-2 first:pt-0 last:pb-0">
                                                            <span
                                                                class="block py-2 px-3 text-xs font-medium uppercase text-gray-400 dark:text-neutral-600">
                                                                Options
                                                            </span>
                                                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                                                                href="#">
                                                                <svg class="shrink-0 size-4"
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <rect width="8" height="4" x="8" y="2"
                                                                        rx="1" ry="1" />
                                                                    <path
                                                                        d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                                                </svg>
                                                                Copy
                                                            </a>
                                                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                                                                href="#">
                                                                <svg class="shrink-0 size-4"
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <polyline points="6 9 6 2 18 2 18 9" />
                                                                    <path
                                                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                                                    <rect width="12" height="8" x="6"
                                                                        y="14" />
                                                                </svg>
                                                                Print
                                                            </a>
                                                        </div>
                                                        <div class="py-2 first:pt-0 last:pb-0">
                                                            <span
                                                                class="block py-2 px-3 text-xs font-medium uppercase text-gray-400 dark:text-neutral-600">
                                                                Download options
                                                            </span>
                                                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                                                                href="#">
                                                                <svg class="shrink-0 size-4"
                                                                    xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z" />
                                                                    <path
                                                                        d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                                </svg>
                                                                Excel
                                                            </a>
                                                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                                                                href="#">
                                                                <svg class="shrink-0 size-4"
                                                                    xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor"
                                                                    viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd"
                                                                        d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z" />
                                                                </svg>
                                                                .CSV
                                                            </a>
                                                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                                                                href="#">
                                                                <svg class="shrink-0 size-4"
                                                                    xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                                    <path
                                                                        d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                                                </svg>
                                                                .PDF
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="hs-dropdown [--placement:bottom-right] relative inline-block"
                                                    data-hs-dropdown-auto-close="inside">
                                                    <button id="hs-as-table-table-filter-dropdown" type="button"
                                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                                                        aria-haspopup="menu" aria-expanded="false"
                                                        aria-label="Dropdown">
                                                        <svg class="shrink-0 size-3.5"
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M3 6h18" />
                                                            <path d="M7 12h10" />
                                                            <path d="M10 18h4" />
                                                        </svg>
                                                        Filter
                                                        <span
                                                            class="ps-2 text-xs font-semibold text-blue-600 border-s border-gray-200 dark:border-neutral-700 dark:text-blue-500">
                                                            1
                                                        </span>
                                                    </button>
                                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-48 z-10 bg-white shadow-md rounded-lg mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700"
                                                        role="menu" aria-orientation="vertical"
                                                        aria-labelledby="hs-as-table-table-filter-dropdown">
                                                        <div class="divide-y divide-gray-200 dark:divide-neutral-700">
                                                            <label for="hs-as-filters-dropdown-all"
                                                                class="flex py-2.5 px-3">
                                                                <input type="checkbox"
                                                                    class="shrink-0 mt-0.5 border-gray-300 rounded-sm text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                                                    id="hs-as-filters-dropdown-all" checked>
                                                                <span
                                                                    class="ms-3 text-sm text-gray-800 dark:text-neutral-200">All</span>
                                                            </label>
                                                            <label for="hs-as-filters-dropdown-published"
                                                                class="flex py-2.5 px-3">
                                                                <input type="checkbox"
                                                                    class="shrink-0 mt-0.5 border-gray-300 rounded-sm text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                                                    id="hs-as-filters-dropdown-published">
                                                                <span
                                                                    class="ms-3 text-sm text-gray-800 dark:text-neutral-200">Published</span>
                                                            </label>
                                                            <label for="hs-as-filters-dropdown-pending"
                                                                class="flex py-2.5 px-3">
                                                                <input type="checkbox"
                                                                    class="shrink-0 mt-0.5 border-gray-300 rounded-sm text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                                                    id="hs-as-filters-dropdown-pending">
                                                                <span
                                                                    class="ms-3 text-sm text-gray-800 dark:text-neutral-200">Pending</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                        <!-- End Header -->

                                        <!-- Table -->
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                                <tr>
                                                    <th class="px-6 py-3 text-start">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                            Product
                                                        </span>
                                                    </th>
                                                    <th class="px-6 py-3 text-center">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                            Qty
                                                        </span>
                                                    </th>
                                                    <th class="px-6 py-3 text-end">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                            Harga
                                                        </span>
                                                    </th>
                                                    <th class="px-6 py-3 text-end">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                            Diskon
                                                        </span>
                                                    </th>
                                                    <th class="px-6 py-3 text-end">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                            Setelah Diskon
                                                        </span>
                                                    </th>
                                                    <th class="px-6 py-3 text-end">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                            Total
                                                        </span>
                                                    </th>
                                                    <th class="px-6 py-3 text-center">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                            Aksi
                                                        </span>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                                @forelse ($this->carts as $index => $cart)
                                                    @php
                                                        $unitPrice = $cart['selling_price'] ?? 0;
                                                        $finalPrice = $cart['final_unit_price'] ?? $unitPrice;
                                                        $qty = $cart['quantity'] ?? 0;
                                                        $lineTotal = $qty * $finalPrice;
                                                        $lineDiscAmt = $cart['discount_amount'] ?? 0;
                                                    @endphp
                                                    <tr>
                                                        {{-- Product --}}
                                                        <td class="px-6 py-4">
                                                            <div class="flex flex-col">
                                                                <span
                                                                    class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                                                    {{ $cart['product_name'] ?? '—' }}
                                                                </span>
                                                                <span
                                                                    class="text-xs text-gray-500 dark:text-neutral-400">
                                                                    {{ $cart['price_type'] ?? 'toko' }}
                                                                </span>
                                                            </div>
                                                        </td>

                                                        {{-- Qty (Alpine + Livewire seperti sebelumnya) --}}
                                                        <td class="px-6 py-4">
                                                            <div x-data="{
                                                                qty: {{ (int) ($cart['quantity'] ?? 1) }},
                                                                max: {{ (int) ($cart['max_quantity'] ?? 0) }},
                                                                clamp() {
                                                                    if (!this.qty || this.qty < 1) this.qty = 1;
                                                                    if (this.max > 0 && this.qty > this.max) this.qty = this.max;
                                                                }
                                                            }"
                                                                class="flex items-center justify-center gap-x-2">
                                                                <button type="button"
                                                                    @click="
                                qty = qty - 1;
                                if (qty < 1) qty = 1;
                                clamp();
                                $wire.updateQuantity({{ $index }}, qty);
                            "
                                                                    class="inline-flex items-center justify-center h-7 w-7 rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                                                    −
                                                                </button>

                                                                <input type="number" x-model.number="qty"
                                                                    min="1" :max="max || null"
                                                                    @input="
                                clamp();
                                $wire.updateQuantity({{ $index }}, qty);
                            "
                                                                    class="w-16 text-center text-sm border border-gray-200 rounded-md bg-gray-50 focus:ring-0 focus:border-gray-400
                                   dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-100 dark:focus:border-neutral-400" />

                                                                <button type="button"
                                                                    @click="
                                qty = qty + 1;
                                clamp();
                                $wire.updateQuantity({{ $index }}, qty);
                            "
                                                                    class="inline-flex items-center justify-center h-7 w-7 rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                                                    +
                                                                </button>
                                                            </div>
                                                            <div
                                                                class="mt-1 text-[11px] text-gray-400 dark:text-neutral-500 text-center">
                                                                Stok: {{ $cart['max_quantity'] ?? 0 }}
                                                            </div>
                                                        </td>

                                                        {{-- Harga sebelum diskon --}}
                                                        <td class="px-6 py-4 text-end">
                                                            @if (($cart['pricing_mode'] ?? 'fixed') === 'editable')
                                                                <div x-data="{
                                                                    price: {{ (float) ($unitPrice ?? 0) }},
                                                                    clamp() {
                                                                        if (this.price < 0 || isNaN(this.price)) {
                                                                            this.price = 0;
                                                                        }
                                                                    }
                                                                }"
                                                                    class="flex items-center justify-end gap-x-2">
                                                                    <input type="number" step="100"
                                                                        {{-- bisa disesuaikan --}} x-model.number="price"
                                                                        @change="
                    clamp();
                    $wire.updateUnitPrice({{ $index }}, price);
                "
                                                                        @blur="
                    clamp();
                    $wire.updateUnitPrice({{ $index }}, price);
                "
                                                                        class="w-28 text-right text-sm border border-gray-200 rounded-md bg-gray-50 px-2 py-1
                       focus:ring-0 focus:border-gray-400
                       dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-100 dark:focus:border-neutral-400" />

                                                                    <span
                                                                        class="text-[11px] text-gray-400 dark:text-neutral-500">
                                                                        (editable)
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <span
                                                                    class="text-sm text-gray-800 dark:text-neutral-200">
                                                                    Rp {{ number_format($unitPrice, 0, ',', '.') }}
                                                                </span>
                                                            @endif
                                                        </td>

                                                        {{-- Info diskon --}}
                                                        <td class="px-6 py-4 text-end">
                                                            @if (!empty($cart['discount_type_id']) && !$cart['manual_discount_off'])
                                                                <div class="flex flex-col items-end">
                                                                    <span
                                                                        class="text-xs font-semibold text-green-700 dark:text-green-400">
                                                                        {{ $cart['discount_label'] ?? 'Diskon' }}
                                                                    </span>
                                                                    <span
                                                                        class="text-xs text-gray-600 dark:text-neutral-300">
                                                                        @if ($cart['discount_mode'] === 'percent')
                                                                            {{ $cart['discount_value'] ?? 0 }}%
                                                                        @elseif($cart['discount_mode'] === 'amount')
                                                                            Rp
                                                                            {{ number_format($cart['discount_value'] ?? 0, 0, ',', '.') }}
                                                                        @endif
                                                                    </span>
                                                                    <span
                                                                        class="text-xs text-red-600 dark:text-red-400">
                                                                        − Rp
                                                                        {{ number_format($lineDiscAmt, 0, ',', '.') }}
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <span
                                                                    class="text-xs text-gray-400 dark:text-neutral-500">
                                                                    Tidak ada diskon
                                                                </span>
                                                            @endif
                                                        </td>

                                                        {{-- Harga setelah diskon (per unit) --}}
                                                        <td class="px-6 py-4 text-end">
                                                            <span
                                                                class="text-sm font-medium text-gray-900 dark:text-neutral-100">
                                                                Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                                            </span>
                                                        </td>

                                                        {{-- Total line (qty x finalUnitPrice) --}}
                                                        <td class="px-6 py-4 text-end">
                                                            <span
                                                                class="text-sm font-semibold text-gray-900 dark:text-neutral-100">
                                                                Rp {{ number_format($lineTotal, 0, ',', '.') }}
                                                            </span>
                                                        </td>

                                                        {{-- Aksi: hapus diskon & hapus item --}}
                                                        <td class="px-6 py-4 text-center">
                                                            <div class="flex flex-col items-center gap-y-1">
                                                                {{-- Hapus diskon hanya item ini --}}
                                                                <button type="button"
                                                                    wire:click="removeDiscountForItem({{ $index }})"
                                                                    class="inline-flex items-center gap-x-1 text-[11px] font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">
                                                                    <span>Hapus Diskon</span>
                                                                </button>

                                                                {{-- Hapus item dari cart (opsi) --}}
                                                                <button type="button"
                                                                    wire:click="removeFromCart({{ $index }})"
                                                                    class="inline-flex items-center gap-x-1 text-[11px] font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                                    <span>Hapus Item</span>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7"
                                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-400">
                                                            Keranjang masih kosong.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        {{-- Grand total --}}
                                        {{-- <div class="mt-4 flex items-center justify-end">
                                            <div
                                                class="inline-flex items-center gap-x-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2
                dark:border-neutral-700 dark:bg-neutral-800">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">
                                                    Total
                                                </span>
                                                <span class="text-lg font-bold text-gray-900 dark:text-neutral-100">
                                                    Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div> --}}


                                        {{-- Grand total --}}
                                        {{-- <div class="mt-4 flex items-center justify-end">
                                            <div
                                                class="inline-flex items-center gap-x-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2
                dark:border-neutral-700 dark:bg-neutral-800">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">
                                                    Total
                                                </span>
                                                <span class="text-lg font-bold text-gray-900 dark:text-neutral-100">
                                                    Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div> --}}

                                        <!-- End Table -->

                                        <!-- Footer -->
                                        {{-- <div
                                        class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                                        <div class="max-w-sm space-y-3">
                                            <select
                                                class="py-2 px-3 pe-9 block border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option selected>5</option>
                                                <option>6</option>
                                            </select>
                                        </div>

                                        <div>
                                            <div class="inline-flex gap-x-2">
                                                <button type="button"
                                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m15 18-6-6 6-6" />
                                                    </svg>
                                                    Prev
                                                </button>

                                                <button type="button"
                                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                                    Next
                                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m9 18 6-6-6-6" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div> --}}
                                        <!-- End Footer -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                    <!-- End Table Section -->

                </div>
            </div>

            <div class="col-span-12 grid grid-cols-1 md:grid-cols-1 gap-8">
                {{-- Summary --}}
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm
               dark:border-neutral-800 dark:bg-neutral-900">
                    <h3 class="mb-3 text-sm font-semibold text-gray-800 dark:text-neutral-100">
                        Ringkasan
                    </h3>

                    <dl class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-neutral-300">Subtotal</dt>
                            <dd class="font-medium text-gray-900 dark:text-neutral-100">
                                Rp {{ number_format($this->subtotal, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-neutral-300">Diskon Item</dt>
                            <dd class="font-medium text-red-600 dark:text-red-400">
                                − Rp {{ number_format($this->itemDiscountTotal, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-neutral-300">Setelah Diskon Item</dt>
                            <dd class="font-medium text-gray-900 dark:text-neutral-100">
                                Rp {{ number_format($this->subtotalAfterItemDiscount, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-neutral-300">Diskon Universal</dt>
                            <dd class="font-medium text-red-600 dark:text-red-400">
                                − Rp {{ number_format($this->universalDiscountAmount, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div
                            class="flex justify-between border-t border-dashed border-gray-200 pt-2 dark:border-neutral-700">
                            <dt class="text-xs font-semibold uppercase text-gray-600 dark:text-neutral-300">
                                Grand Total
                            </dt>
                            <dd class="text-lg font-bold text-gray-900 dark:text-neutral-100">
                                Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Pembayaran --}}
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm
               dark:border-neutral-800 dark:bg-neutral-900">
                    <h3 class="mb-3 text-sm font-semibold text-gray-800 dark:text-neutral-100">
                        Pembayaran
                    </h3>

                    <div class="space-y-3 text-sm">

                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600 dark:text-neutral-300">
                                Uang Diterima
                            </label>
                            <input type="number" wire:model.lazy="amountPaid"
                                class="block w-full rounded-lg border-gray-200 px-3 py-2 text-right text-sm text-gray-800
                           focus:border-gray-400 focus:ring-0
                           dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-neutral-500"
                                placeholder="0" />
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-neutral-300">Kembalian</span>
                            <span class="font-semibold text-gray-900 dark:text-neutral-100">
                                Rp {{ number_format($this->changeAmount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <x-filament::button type="button" color="gray" wire:click="resetCart"
                            class="flex-1 justify-center">
                            Batal
                        </x-filament::button>

                        <x-filament::button type="button" color="primary" wire:click="checkout"
                            class="flex-1 justify-center">
                            Simpan / Bayar
                        </x-filament::button>
                    </div>
                </div>

            </div>
        @endif

        @if ($activeTab === 'products')
            <div class="col-span-12 flex flex-col md:flex-col lg:flex-row">
                <div class="grid grid-cols-1 min-w-full gap-8">
                    <x-filament::input.wrapper class="w-full">
                        <x-filament::input type="text" wire:model.live.debounce.500ms="search"
                            placeholder="Ketik nama barang..." />
                    </x-filament::input.wrapper>
                </div>
            </div>
            <div class="col-span-12 flex flex-col md:flex-col lg:flex-row">
                <div class="grid grid-cols-2 min-w-full gap-8">

                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.change="productCategoryId" searchable>
                            <option value="">--- Pilih Kategori ---</option>
                            @foreach ($this->productCategories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>

                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.change="brandId" searchable>
                            <option value="">--- Pilih Merk ---</option>
                            @foreach ($this->brands as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>

                </div>
            </div>

            <div class="col-span-12 flex flex-col md:flex-col lg:flex-row gap-8">

                <div class="grid grid-cols-4 min-w-full gap-8">
                    @foreach ($this->products as $product)
                        <div class="group flex flex-col">
                            <div class="relative">
                                <div x-data="{
                                    images: @js($product->product->getMedia('productImages')->map->getUrl()),
                                    activeIndex: 0,
                                    next() {
                                        if (this.images.length === 0) return;
                                        this.activeIndex = (this.activeIndex + 1) % this.images.length;
                                    },
                                    prev() {
                                        if (this.images.length === 0) return;
                                        this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                                    },
                                }" class="relative">
                                    <div class="rounded-3xl bg-white dark:bg-neutral-800 p-4">
                                        <div class="aspect-square overflow-hidden rounded-2xl">
                                            <template x-if="images.length === 0">
                                                <div
                                                    class="flex h-full w-full items-center justify-center text-sm text-neutral-400">
                                                    Tidak ada gambar
                                                </div>
                                            </template>

                                            <template x-if="images.length > 0">
                                                <img class="h-full w-full object-cover" :src="images[activeIndex]"
                                                    alt="Foto produk" loading="lazy">
                                            </template>
                                        </div>
                                    </div>
                                    <!-- tombol prev/next + dots seperti yang tadi -->
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
                                <button type="button" @click="$wire.addToCart('{{ $product->id }}')"
                                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-yellow-500 text-white hover:bg-yellow-600 focus:outline-hidden focus:bg-yellow-600 disabled:opacity-50 disabled:pointer-events-none">
                                    Masukkan Keranjang
                                </button>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
            <div class="col-span-12">
                {{ $this->products->links('vendor.livewire.simple-tailwind') }}
            </div>
        @endif
    </div>



</x-filament-panels::page>
