<div>
    {{-- Preline Tab Navigation --}}
    <div class="mb-6 flex justify-center sm:justify-start">
        <div
            class="flex bg-gray-100 hover:bg-gray-200 rounded-lg transition p-1 dark:bg-neutral-800 dark:hover:bg-neutral-800">
            <nav class="flex gap-x-1" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                <button type="button" wire:click="$set('activeTab', 'carts')"
                    class="py-2.5 px-6 inline-flex items-center gap-x-2 bg-transparent text-sm text-gray-500 hover:text-gray-700 font-medium rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white {{ $activeTab === 'carts' ? 'bg-white text-gray-700 shadow-sm dark:bg-neutral-900 dark:text-white' : '' }}"
                    role="tab" aria-selected="{{ $activeTab === 'carts' ? 'true' : 'false' }}">
                    <svg class="shrink-0 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Keranjang
                </button>
                <button type="button" wire:click="$set('activeTab', 'products')"
                    class="py-2.5 px-6 inline-flex items-center gap-x-2 bg-transparent text-sm text-gray-500 hover:text-gray-700 font-medium rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white {{ $activeTab === 'products' ? 'bg-white text-gray-700 shadow-sm dark:bg-neutral-900 dark:text-white' : '' }}"
                    role="tab" aria-selected="{{ $activeTab === 'products' ? 'true' : 'false' }}">
                    <svg class="shrink-0 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Produk
                </button>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4 sm:gap-6 lg:gap-8">
        @if ($activeTab === 'carts')
            <div class="col-span-12">
                <div
                    class="flex flex-col gap-4 bg-white border border-gray-200 shadow-sm rounded-xl p-4 md:p-5 dark:bg-neutral-900 dark:border-neutral-800">

                    {{-- Row atas: Judul + info kasir/toko --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="shrink-0 flex justify-center items-center size-11 bg-blue-100 text-blue-700 rounded-lg dark:bg-blue-900/40 dark:text-blue-300">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                    Kasir POS
                                </h2>
                                <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-neutral-500 mt-1">
                                    Toko: <span
                                        class="font-bold text-gray-700 dark:text-neutral-300">{{ $this->activeStore?->name ?? '-' }}</span>
                                    <span class="mx-1 text-gray-300 dark:text-neutral-600">•</span>
                                    Kasir: <span
                                        class="font-bold text-gray-700 dark:text-neutral-300">{{ auth()->user()->name ?? '-' }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Info ringkas waktu --}}
                        <div
                            class="flex items-center gap-2 text-[11px] font-medium text-gray-600 dark:text-neutral-400">
                            <span
                                class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-50 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ now()->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>

                    {{-- Row tengah: Customer + Payment method + Diskon --}}
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-4">
                        {{-- Mode Transaksi --}}
                        <div class="space-y-2">
                            <label class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200">
                                <span class="flex items-center gap-2">
                                    <svg class="size-4 text-gray-500 dark:text-neutral-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mode Transaksi
                                </span>
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" wire:click="$set('checkoutMode', 'normal')"
                                    @class([
                                        'inline-flex justify-center items-center gap-x-2 py-2.5 px-3 text-sm font-medium rounded-lg border focus:outline-none disabled:opacity-50 disabled:pointer-events-none transition-all',
                                        'bg-blue-600 border-blue-600 text-white hover:bg-blue-700' =>
                                            $checkoutMode === 'normal',
                                        'bg-white border-gray-200 text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800' =>
                                            $checkoutMode !== 'normal',
                                    ])>
                                    Biasa
                                </button>
                                <button type="button" wire:click="$set('checkoutMode', 'service')"
                                    @class([
                                        'inline-flex justify-center items-center gap-x-2 py-2.5 px-3 text-sm font-medium rounded-lg border focus:outline-none disabled:opacity-50 disabled:pointer-events-none transition-all',
                                        'bg-amber-500 border-amber-500 text-white hover:bg-amber-600' =>
                                            $checkoutMode === 'service',
                                        'bg-white border-gray-200 text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800' =>
                                            $checkoutMode !== 'service',
                                    ])>
                                    Service
                                </button>
                            </div>
                        </div>

                        {{-- Pilih Customer --}}
                        <div class="space-y-2">
                            <label class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200">
                                <span class="flex items-center gap-2">
                                    <svg class="size-4 text-gray-500 dark:text-neutral-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.856-1.488M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z">
                                        </path>
                                    </svg>
                                    Pelanggan
                                </span>
                            </label>
                            <select wire:model="customerId"
                                class="py-2.5 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                                <option value="">Walk-in / Umum</option>
                                @foreach ($customerOptions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Payment Method --}}
                        <div class="space-y-2">
                            <label class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200">
                                <span class="flex items-center gap-2">
                                    <svg class="size-4 text-gray-500 dark:text-neutral-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM6 6h.01M6 10h.01M6 14h.01M6 18h.01">
                                        </path>
                                    </svg>
                                    Metode Pembayaran
                                </span>
                            </label>
                            <select wire:model.change="paymentId"
                                class="py-2.5 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                                <option value="">Pilih metode</option>
                                @foreach ($paymentOptions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jenis Diskon Item --}}
                        <div class="space-y-2">
                            <label class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200">
                                <span class="flex items-center gap-2">
                                    <svg class="size-4 text-gray-500 dark:text-neutral-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    Jenis Diskon
                                </span>
                            </label>
                            <select wire:model.change="selectedDiscountTypeId"
                                class="py-2.5 px-4 pe-9 block w-full {{ $selectedDiscountTypeId ? 'border-teal-500 focus:border-teal-500 focus:ring-teal-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }} rounded-lg text-sm disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                                <option value="">Tanpa Diskon</option>
                                @foreach ($discountTypeOptions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Service Order Dropdown (conditional) --}}
                    @if ($checkoutMode === 'service')
                        <div
                            class="bg-amber-50 border border-amber-200 rounded-lg p-4 dark:bg-amber-900/10 dark:border-amber-900/50 mb-4 transition-all duration-300">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 items-end">
                                <div class="space-y-2 lg:col-span-2">
                                    <label class="inline-block text-sm font-medium text-amber-800 dark:text-amber-200">
                                        <span class="flex items-center gap-2">
                                            <svg class="size-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                </path>
                                            </svg>
                                            Pilih Service Order
                                        </span>
                                    </label>
                                    <select wire:model.change="serviceOrderId"
                                        class="py-2.5 px-4 pe-9 block w-full border-amber-200 rounded-lg text-sm focus:border-amber-500 focus:ring-amber-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                                        <option value="">— Silahkan Pilih —</option>
                                        @foreach ($this->serviceOrderOptions as $id => $number)
                                            <option value="{{ $id }}">{{ $number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <p
                                        class="text-xs text-amber-600 dark:text-amber-400 flex gap-1.5 items-start mt-2">
                                        <svg class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 8a6 6 0 06-12 0 6 6 0 0112 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V4a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Item part & jasa akan otomatis ditambah ke keranjang.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Diskon Universal Section --}}
                    @if ($universalDiscountMode)
                        <div
                            class="bg-purple-50 border border-purple-200 rounded-lg p-4 dark:bg-purple-900/10 dark:border-purple-900/50 mb-4 transition-all duration-300">
                            <label class="inline-block text-sm font-medium text-purple-800 dark:text-purple-200 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Diskon Keseluruhan Transaksi
                                </span>
                            </label>
                            <div class="flex gap-3 items-start">
                                <select wire:model="universalDiscountMode"
                                    class="py-2.5 px-4 block w-32 border-purple-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                    <option value="">-</option>
                                    <option value="percent">Persen %</option>
                                    <option value="amount">Nominal Rp</option>
                                </select>
                                <input type="number" step="0.01" min="0"
                                    wire:model.lazy="universalDiscountValue"
                                    class="py-2.5 px-4 block w-full border-purple-200 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                                    placeholder="0" />
                                <button type="button" wire:click="$set('universalDiscountMode', null)"
                                    class="inline-flex justify-center items-center size-[42px] shrink-0 text-sm font-medium rounded-lg border border-purple-200 bg-white text-purple-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-purple-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                    <svg class="shrink-0 size-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-2 text-xs font-medium text-purple-600 dark:text-purple-400">
                                Berlaku ke total akhir setelah diskon per-item
                            </p>
                        </div>
                    @else
                        <div class="mb-4">
                            <button type="button" wire:click="$set('universalDiscountMode', 'percent')"
                                class="inline-flex items-center gap-x-2 py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                <svg class="shrink-0 size-4 text-purple-600 dark:text-purple-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Diskon Keseluruhan
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<div class="col-span-12 flex flex-col md:flex-col lg:flex-row gap-8">
    <div class="grid grid-cols-1 min-w-full gap-8">
        @if ($activeTab === 'carts')
            <div class="col-span-12 lg:col-span-8">
                <!-- Table Section -->
                <div class="flex flex-col">
                    <div class="mb-3 flex items-center justify-between px-1">
                        <h3
                            class="text-sm font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200 flex items-center gap-2">
                            <svg class="size-5 text-gray-500 dark:text-neutral-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Keranjang Belanja
                        </h3>
                        <span
                            class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                            {{ count($this->carts) }} item
                        </span>
                    </div>

                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div
                                class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-800">
                                <!-- Table -->
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                    <thead class="bg-gray-50 dark:bg-neutral-800">
                                        <tr>
                                            <th scope="col" class="px-5 py-3 text-start">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                    Produk
                                                </span>
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                    Qty
                                                </span>
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-end">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                    Harga Unit
                                                </span>
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-end">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                    Setelah Diskon
                                                </span>
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-end">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                    Total
                                                </span>
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
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
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                                                {{-- Product --}}
                                                <td class="px-5 py-3 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                            {{ $cart['product_name'] ?? '—' }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-neutral-400">
                                                            {{ $cart['price_type'] ?? 'toko' }}
                                                        </span>
                                                    </div>
                                                </td>

                                                {{-- Qty (Alpine + Livewire seperti sebelumnya) --}}
                                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                                    <div x-data="{
                                                        qty: {{ (int) ($cart['quantity'] ?? 1) }},
                                                        max: {{ (int) ($cart['max_quantity'] ?? 0) }},
                                                        clamp() {
                                                            if (!this.qty || this.qty < 1) this.qty = 1;
                                                            if (this.max > 0 && this.qty > this.max) this.qty = this.max;
                                                        }
                                                    }"
                                                        class="flex flex-col items-center gap-1">
                                                        <div
                                                            class="inline-flex items-center gap-x-1.5 bg-white border border-gray-200 rounded-lg dark:bg-neutral-900 dark:border-neutral-700">
                                                            <button type="button"
                                                                @click="qty = qty - 1; clamp(); $wire.updateQuantity({{ $index }}, qty);"
                                                                class="size-7 inline-flex justify-center items-center rounded-md text-gray-500 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800">
                                                                <svg class="shrink-0 size-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M20 12H4"></path>
                                                                </svg>
                                                            </button>
                                                            <input type="number" x-model.number="qty" min="1"
                                                                :max="max || null"
                                                                @input="clamp(); $wire.updateQuantity({{ $index }}, qty);"
                                                                class="p-0 w-8 bg-transparent border-0 text-center text-sm font-medium focus:ring-0 dark:text-white" />
                                                            <button type="button"
                                                                @click="qty = qty + 1; clamp(); $wire.updateQuantity({{ $index }}, qty);"
                                                                class="size-7 inline-flex justify-center items-center rounded-md text-gray-500 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800">
                                                                <svg class="shrink-0 size-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <span
                                                            class="text-[10px] sm:text-[11px] text-gray-500 dark:text-neutral-500">
                                                            Stok: {{ $cart['max_quantity'] ?? 0 }}
                                                        </span>
                                                    </div>
                                                </td>

                                                {{-- Harga sebelum diskon --}}
                                                <td class="px-5 py-3 text-end whitespace-nowrap">
                                                    @if (($cart['pricing_mode'] ?? 'fixed') === 'editable')
                                                        <div x-data="{
                                                            price: {{ (float) ($unitPrice ?? 0) }},
                                                            clamp() {
                                                                if (this.price < 0 || isNaN(this.price)) {
                                                                    this.price = 0;
                                                                }
                                                            }
                                                        }"
                                                            class="flex flex-col items-end gap-1">
                                                            <input type="number" step="100"
                                                                x-model.number="price"
                                                                @change="clamp(); $wire.updateUnitPrice({{ $index }}, price);"
                                                                @blur="clamp(); $wire.updateUnitPrice({{ $index }}, price);"
                                                                class="py-1.5 px-3 w-28 text-right block border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" />
                                                            <span
                                                                class="text-[10px] text-gray-400 dark:text-neutral-500">(editable)</span>
                                                        </div>
                                                    @else
                                                        <span class="text-sm text-gray-800 dark:text-neutral-200">
                                                            Rp {{ number_format($unitPrice, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Harga setelah diskon (per unit) --}}
                                                <td class="px-5 py-3 text-end whitespace-nowrap">
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                            Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                                        </span>
                                                        @if (!empty($cart['discount_type_id']) && !$cart['manual_discount_off'])
                                                            <div class="flex items-center gap-1 mt-0.5">
                                                                <span
                                                                    class="inline-flex items-center gap-x-1 py-0.5 px-1.5 rounded text-[10px] font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                                                                    @if ($cart['discount_mode'] === 'percent')
                                                                        {{ $cart['discount_value'] ?? 0 }}%
                                                                    @else
                                                                        Rp
                                                                        {{ number_format($cart['discount_value'] ?? 0, 0, ',', '.') }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>

                                                {{-- Total line (qty x finalUnitPrice) --}}
                                                <td class="px-5 py-3 text-end whitespace-nowrap">
                                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                                        Rp {{ number_format($lineTotal, 0, ',', '.') }}
                                                    </span>
                                                </td>

                                                {{-- Aksi: hapus diskon & hapus item --}}
                                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                                    <div class="flex flex-col items-center justify-center gap-y-2">
                                                        {{-- Hapus diskon hanya item ini --}}
                                                        @if (!empty($cart['discount_type_id']) && !$cart['manual_discount_off'])
                                                            <button type="button"
                                                                wire:click="removeDiscountForItem({{ $index }})"
                                                                title="Hapus Diskon"
                                                                class="inline-flex justify-center items-center size-6 rounded bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-900/40 dark:text-amber-400 dark:hover:bg-amber-900/60 transition-colors">
                                                                <svg class="shrink-0 size-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"></path>
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        {{-- Hapus item dari cart (opsi) --}}
                                                        <button type="button"
                                                            wire:click="removeFromCart({{ $index }})"
                                                            title="Hapus Item"
                                                            class="inline-flex justify-center items-center size-6 rounded bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/40 dark:text-red-400 dark:hover:bg-red-900/60 transition-colors">
                                                            <svg class="shrink-0 size-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-5 py-12 text-center whitespace-nowrap">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div
                                                            class="h-12 w-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-3 dark:bg-neutral-800 dark:text-neutral-500">
                                                            <svg class="size-6" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="1.5"
                                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <p
                                                            class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                            Keranjang Belanja Kosong
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-neutral-500 mt-1">
                                                            Silahkan tambahkan produk dari tab "Produk"
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Receipt modal (iframe for print preview) -->
                        <div x-data="{ open: false, src: '', loading: true }" x-init="window.addEventListener('show-receipt', e => {
                            src = e.detail.receiptUrl || '';
                            loading = true;
                            open = true;
                        });" x-show="open" x-cloak>
                            <div
                                class="fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto bg-gray-900/50 dark:bg-neutral-900/80 backdrop-blur-sm transition-all flex items-center justify-center">
                                <div class="w-[880px] max-w-[95%] h-[90vh] flex flex-col bg-white dark:bg-neutral-900 rounded-xl shadow-2xl border border-gray-200 dark:border-neutral-700 overflow-hidden"
                                    @click.stop>
                                    <div
                                        class="flex items-center justify-between py-3 px-4 border-b border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800/50">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="size-10 flex justify-center items-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-800/30 dark:text-blue-500">
                                                <svg class="shrink-0 size-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-800 dark:text-neutral-200 text-sm">
                                                    Preview Struk</h3>
                                                <p class="text-[11px] text-gray-500 dark:text-neutral-500">Struk
                                                    transaksi — bisa dicetak atau dibuka di tab baru</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button x-bind:disabled="loading"
                                                @click="if($refs.ifr && $refs.ifr.contentWindow) { $refs.ifr.contentWindow.focus(); $refs.ifr.contentWindow.print(); }"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none transition-colors">
                                                <svg class="shrink-0 size-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                    </path>
                                                </svg>
                                                Print
                                            </button>

                                            <a :href="src" target="_blank" rel="noopener"
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 transition-colors">
                                                <svg class="shrink-0 size-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                                Buka di Tab Baru
                                            </a>

                                            <div class="w-px h-6 bg-gray-200 dark:bg-neutral-700 mx-1"></div>

                                            <button @click="open=false; src='';" type="button"
                                                class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-white/10 dark:hover:bg-white/20 dark:text-white dark:focus:bg-white/20 transition-colors">
                                                <span class="sr-only">Tutup</span>
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="relative flex-1 bg-white dark:bg-neutral-900">
                                        <!-- loading overlay -->
                                        <div x-show="loading"
                                            class="absolute inset-0 z-40 flex items-center justify-center bg-white/80 dark:bg-neutral-900/80 backdrop-blur-sm">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="animate-spin inline-block size-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <div class="text-sm font-medium text-gray-600 dark:text-neutral-400">
                                                    Memuat struk transaksi...</div>
                                            </div>
                                        </div>

                                        <iframe x-ref="ifr" :src="src"
                                            class="w-full h-full align-bottom" frameborder="0"
                                            @load="loading=false"></iframe>
                                    </div>
                                </div>
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
        class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl p-4 md:p-5 dark:bg-neutral-900 dark:border-neutral-800">
        <div class="flex items-center gap-2 mb-4">
            <svg class="size-5 text-gray-500 dark:text-neutral-500" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                Ringkasan Total
            </h3>
        </div>

        <ul class="flex flex-col gap-y-3">
            <li class="flex items-center justify-between text-sm">
                <span class="text-gray-500 dark:text-neutral-500">Subtotal</span>
                <span class="font-medium text-gray-800 dark:text-neutral-200">Rp
                    {{ number_format($this->subtotal, 0, ',', '.') }}</span>
            </li>

            @if ($this->itemDiscountTotal > 0)
                <li class="flex items-center justify-between text-sm">
                    <span class="flex items-center gap-1.5 text-red-500 dark:text-red-400">
                        <svg class="shrink-0 size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                            </path>
                        </svg>
                        Diskon Item
                    </span>
                    <span class="font-medium text-red-500 dark:text-red-400">− Rp
                        {{ number_format($this->itemDiscountTotal, 0, ',', '.') }}</span>
                </li>
            @endif

            <li
                class="flex items-center justify-between text-sm py-2 border-y border-gray-200 dark:border-neutral-800">
                <span class="font-medium text-gray-800 dark:text-neutral-200">Setelah Diskon Item</span>
                <span class="font-semibold text-gray-800 dark:text-neutral-200">Rp
                    {{ number_format($this->subtotalAfterItemDiscount, 0, ',', '.') }}</span>
            </li>

            @if ($this->universalDiscountAmount > 0)
                <li class="flex items-center justify-between text-sm pt-1">
                    <span class="flex items-center gap-1.5 text-purple-600 dark:text-purple-400">
                        <svg class="shrink-0 size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Diskon Keseluruhan
                    </span>
                    <span class="font-medium text-purple-600 dark:text-purple-400">− Rp
                        {{ number_format($this->universalDiscountAmount, 0, ',', '.') }}</span>
                </li>
            @endif

            <li class="flex items-center justify-between pt-3 mt-1 border-t border-gray-200 dark:border-neutral-800">
                <span class="text-base font-bold text-gray-800 dark:text-neutral-200">Grand Total</span>
                <span class="text-xl font-bold text-blue-600 dark:text-blue-500">Rp
                    {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
            </li>
        </ul>
    </div>

    {{-- Pembayaran --}}
    <div
        class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl p-4 md:p-5 dark:bg-neutral-900 dark:border-neutral-800">
        <div class="flex items-center gap-2 mb-4">
            <svg class="size-5 text-gray-500 dark:text-neutral-500" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM6 6h.01M6 10h.01M6 14h.01M6 18h.01"></path>
            </svg>
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                Pembayaran
            </h3>
        </div>

        <div class="space-y-4">
            <div>
                <label class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200 mb-2">
                    Status Pembayaran
                </label>
                <select wire:model.lazy="paymentStatus"
                    class="py-2.5 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                    <option value="unpaid">Belum Dibayar</option>
                    <option value="partial">Sebagian</option>
                    <option value="paid">Lunas</option>
                    <option value="refunded">Dikembalikan</option>
                </select>

                <p class="mt-2 text-xs text-gray-500 dark:text-neutral-500 flex items-start gap-1.5">
                    <svg class="shrink-0 size-4 text-blue-500 dark:text-blue-400 mt-0.5" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Bila tunai, masukkan jumlah uang yang diterima.
                </p>
            </div>

            @if ($paymentStatus !== 'unpaid')
                <div
                    class="space-y-4 bg-gray-50 p-4 rounded-xl dark:bg-neutral-800/50 border border-gray-100 dark:border-neutral-800 transition-all">
                    <div>
                        <label
                            class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200 mb-2 flex items-center gap-1.5">
                            <svg class="shrink-0 size-4 text-gray-500 dark:text-neutral-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Uang Diterima
                        </label>
                        <div class="relative">
                            <input type="number" min="0" step="100" wire:model.lazy="amountPaid"
                                class="py-3 px-4 ps-11 block w-full border-gray-200 rounded-lg text-sm text-right focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600"
                                placeholder="0" />
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                <span class="text-gray-500 dark:text-neutral-500 font-medium text-sm">Rp</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div
                            class="bg-red-50 border border-red-100 rounded-xl p-3 dark:bg-red-900/30 dark:border-red-900/50">
                            <p
                                class="text-[11px] font-semibold text-red-600 uppercase tracking-wide dark:text-red-400 mb-1">
                                Sisa Bayar</p>
                            <p class="text-base font-bold text-red-700 dark:text-red-300">
                                Rp {{ number_format(max(0, $this->grandTotal - $this->amountPaid), 0, ',', '.') }}
                            </p>
                        </div>

                        <div
                            class="bg-teal-50 border border-teal-100 rounded-xl p-3 dark:bg-teal-900/30 dark:border-teal-900/50">
                            <p
                                class="text-[11px] font-semibold text-teal-600 uppercase tracking-wide dark:text-teal-400 mb-1">
                                Kembalian</p>
                            <p class="text-base font-bold text-teal-700 dark:text-teal-300">
                                Rp {{ number_format($this->changeAmount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
            <button type="button" wire:click="resetCart"
                class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800 transition-colors">
                <svg class="shrink-0 size-4 text-gray-500 dark:text-neutral-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
                Batal
            </button>

            <button type="button" wire:click="checkout"
                class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-teal-600 text-white hover:bg-teal-700 focus:outline-none focus:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none transition-colors shadow-sm">
                <svg class="shrink-0 size-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan & Bayar
            </button>
        </div>
    </div>

</div>
@endif

@if ($activeTab === 'products')
    {{-- Search Section --}}
    <div class="col-span-12 mb-4">
        <div
            class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl p-4 md:p-5 dark:bg-neutral-900 dark:border-neutral-800 transition-colors">
            <div class="flex items-center gap-2 mb-4">
                <svg class="size-5 text-gray-500 dark:text-neutral-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                    Pencarian & Filter
                </h4>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                {{-- Search Bar --}}
                <div class="lg:col-span-2 space-y-2">
                    <label class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200">
                        Pencarian Produk
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.500ms="search"
                            class="py-2.5 px-4 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600"
                            placeholder="Cari nama barang, keyword, SKU..." />
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                            <svg class="shrink-0 size-4 text-gray-400 dark:text-neutral-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="space-y-2">
                    <label
                        class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200 flex items-center gap-2">
                        <svg class="shrink-0 size-4 text-gray-500 dark:text-neutral-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                            </path>
                        </svg>
                        Kategori
                    </label>
                    <select wire:model.change="productCategoryId"
                        class="py-2.5 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                        <option value="">Semua Kategori</option>
                        @foreach ($this->productCategories as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Brand Filter --}}
                <div class="space-y-2">
                    <label
                        class="inline-block text-sm font-medium text-gray-800 dark:text-neutral-200 flex items-center gap-2">
                        <svg class="shrink-0 size-4 text-gray-500 dark:text-neutral-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        Merk/Brand
                    </label>
                    <select wire:model.change="brandId"
                        class="py-2.5 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                        <option value="">Semua Merk</option>
                        @foreach ($this->brands as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12">
        <div class="grid grid-cols-2 lg:grid-cols-4 sm:grid-cols-3 gap-4 sm:gap-6 w-full">
            @foreach ($this->products as $product)
                @php
                    $images = $product->product->getMedia('productImages');
                @endphp

                <div
                    class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-800 transition-all hover:shadow-md hover:border-blue-500/50 dark:hover:border-blue-500/50">
                    {{-- Image Section --}}
                    <div class="relative p-3">
                        <div
                            class="relative w-full overflow-hidden rounded-lg bg-gray-100 dark:bg-neutral-800 aspect-square">
                            @if ($images->isEmpty())
                                <div
                                    class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-neutral-500">
                                    <svg class="size-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-full h-full relative" data-product-carousel>
                                    @foreach ($images as $index => $media)
                                        <img data-carousel-image data-index="{{ $index }}"
                                            src="{{ $media->getUrl() }}"
                                            alt="Foto produk {{ $product->product->name }} - {{ $index + 1 }}"
                                            class="absolute inset-0 h-full w-full object-cover transition-opacity duration-300 ease-out {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
                                            loading="lazy">
                                    @endforeach

                                    @if ($images->count() > 1)
                                        <div
                                            class="absolute inset-0 flex items-center justify-between px-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" data-carousel-prev
                                                class="size-7 inline-flex justify-center items-center gap-x-2 rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                <svg class="shrink-0 size-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            <button type="button" data-carousel-next
                                                class="size-7 inline-flex justify-center items-center gap-x-2 rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                <svg class="shrink-0 size-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div
                                            class="absolute inset-x-0 bottom-2 flex items-center justify-center gap-1.5 px-2 w-full">
                                            @foreach ($images as $index => $media)
                                                <button type="button" data-carousel-dot
                                                    data-index="{{ $index }}"
                                                    class="size-1.5 rounded-full outline-none transition-all duration-200 {{ $index === 0 ? 'bg-white' : 'bg-white/50 hover:bg-white/75' }}"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="absolute top-4 end-4">
                            <span
                                class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-md text-xs font-medium bg-white/90 text-gray-800 backdrop-blur-sm shadow-sm dark:bg-neutral-900/90 dark:text-neutral-200">
                                Stok: {{ $product->quantity }}
                            </span>
                        </div>
                    </div>

                    {{-- Product Details --}}
                    <div class="flex flex-col flex-1 p-4 md:p-5 pt-0">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white line-clamp-2"
                                title="{{ $product->product->name }}">
                                {{ $product->product->name }}
                            </h3>
                        </div>

                        <div class="mb-3">
                            <p
                                class="text-[11px] font-medium text-gray-500 dark:text-neutral-500 uppercase tracking-wide mb-0.5">
                                Harga Jual</p>
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-500">
                                Rp {{ number_format($product->productPrice->selling_price ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <ul class="text-[11px] text-gray-500 dark:text-neutral-500 space-y-1 mt-auto pb-4">
                            <li class="flex items-center overflow-hidden">
                                <span class="font-medium text-gray-800 dark:text-neutral-300 min-w-[50px]">Tipe:</span>
                                <span class="truncate">{{ $product->product->type ?: '-' }}</span>
                            </li>
                            <li class="flex items-center overflow-hidden">
                                <span
                                    class="font-medium text-gray-800 dark:text-neutral-300 min-w-[50px]">Ukuran:</span>
                                <span class="truncate">{{ $product->product->size ?: '-' }}</span>
                            </li>
                            <li class="flex items-start overflow-hidden">
                                <span class="font-medium text-gray-800 dark:text-neutral-300 min-w-[50px]">Komp:</span>
                                <span
                                    class="truncate line-clamp-2 h-[32px]">{{ $product->product->compatibility ?: '-' }}</span>
                            </li>
                        </ul>

                        {{-- Add to Cart Button --}}
                        <div class="mt-auto pt-3 border-t border-gray-100 dark:border-neutral-800">
                            <button type="button" @click="$wire.addToCart('{{ $product->id }}')"
                                class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-blue-700 transition-colors drop-shadow-sm">
                                <svg class="shrink-0 size-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                                Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div
        class="col-span-12 flex items-center justify-center rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        {{ $this->products->links('vendor.livewire.simple-tailwind') }}
    </div>
@endif
</div>
<script>
    function initProductCarousels(root = document) {
        const carousels = root.querySelectorAll('[data-product-carousel]');

        carousels.forEach((carousel) => {
            // Cegah double-init
            if (carousel.dataset.initialized === 'true') return;
            carousel.dataset.initialized = 'true';

            const slides = carousel.querySelectorAll('[data-carousel-image]');
            const prev = carousel.querySelector('[data-carousel-prev]');
            const next = carousel.querySelector('[data-carousel-next]');
            const dots = carousel.querySelectorAll('[data-carousel-dot]');

            if (!slides.length) return;

            let current = 0;

            function show(index) {
                const total = slides.length;
                if (!total) return;

                current = (index + total) % total;

                slides.forEach((slide, i) => {
                    const isActive = i === current;
                    slide.classList.toggle('opacity-100', isActive);
                    slide.classList.toggle('opacity-0', !isActive);
                    slide.classList.toggle('pointer-events-none', !isActive);
                });

                dots.forEach((dot, i) => {
                    const isActive = i === current;
                    dot.classList.toggle('bg-white/90', isActive);
                    dot.classList.toggle('w-6', isActive);
                    dot.classList.toggle('bg-white/40', !isActive);
                    dot.classList.toggle('w-3', !isActive);
                });
            }

            if (prev) {
                prev.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    show(current - 1);
                });
            }

            if (next) {
                next.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    show(current + 1);
                });
            }

            dots.forEach((dot) => {
                dot.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const index = parseInt(dot.dataset.index, 10) || 0;
                    show(index);
                });
            });

            // Tampilkan slide awal
            show(0);
        });
    }

    // Pertama kali page load (non-Livewire)
    document.addEventListener('DOMContentLoaded', () => {
        initProductCarousels();
    });

    // Livewire v2 (dipakai Filament 3 default)
    document.addEventListener('livewire:load', () => {
        initProductCarousels();
        if (window.Livewire && typeof Livewire.hook === 'function') {
            // Dipanggil tiap kali komponen Livewire selesai di-update
            try {
                Livewire.hook('message.processed', (message, component) => {
                    initProductCarousels(component.el ?? document);
                });
            } catch (e) {}
        }
    });

    // Livewire v3 (kalau kamu sudah upgrade)
    document.addEventListener('livewire:init', () => {

        if (window.Livewire && typeof Livewire.hook === 'function') {
            try {
                Livewire.hook('morph.updated', ({
                    el,
                    component
                }) => {
                    initProductCarousels(el || document);
                });
            } catch (e) {}
        }
    });

    // Untuk wire:navigate (jika dipakai di panel / halaman lain)
    document.addEventListener('livewire:navigated', () => {
        initProductCarousels();
    });
</script>
</div>
