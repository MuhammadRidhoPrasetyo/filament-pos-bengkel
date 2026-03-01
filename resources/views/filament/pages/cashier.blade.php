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

    <div class="grid grid-cols-12 text-gray-900 dark:text-neutral-100 gap-4 md:gap-6 lg:gap-8">
        @if ($activeTab === 'carts')
            <div class="col-span-12">
                <div
                    class="flex flex-col gap-3 md:gap-4 rounded-2xl border-2 border-sky-200/60 bg-white p-3 md:p-4 shadow-lg dark:border-neutral-800 dark:bg-neutral-900">

                    {{-- Row atas: Judul + info kasir/toko --}}
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-100 text-sky-700 shadow-sm
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
                        <div class="flex items-center gap-2 text-[11px] text-gray-600 dark:text-neutral-400">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-3 py-1.5 shadow-md font-medium text-sky-700 dark:bg-sky-900/40
                           dark:text-sky-300 dark:bg-neutral-800/80">
                                <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                <span> {{ now()->format('d M Y H:i') }} </span>
                            </span>
                        </div>
                    </div>

                    {{-- Row tengah: Customer + Payment method + Diskon --}}

                    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 mb-4">
                        {{-- Mode Transaksi Tab --}}
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mode Transaksi
                                </span>
                            </label>

                            <div class="grid grid-cols-2 gap-2">
                                <!-- Normal Mode Button -->
                                <button type="button" wire:click="$set('checkoutMode', 'normal')"
                                    @class([
                                        'relative inline-flex items-center gap-2 px-3 py-2.5 rounded-lg font-medium text-sm transition-all duration-200',
                                        'bg-sky-600 text-white shadow-lg hover:bg-sky-700 border-2 border-sky-600' =>
                                            $checkoutMode === 'normal',
                                        'bg-white text-gray-700 border-2 border-gray-200 hover:border-gray-300 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700 dark:hover:border-neutral-600' =>
                                            $checkoutMode !== 'normal',
                                    ])>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>Biasa</span>
                                </button>

                                <!-- Service Mode Button -->
                                <button type="button" wire:click="$set('checkoutMode', 'service')"
                                    @class([
                                        'relative inline-flex items-center gap-2 px-3 py-2.5 rounded-lg font-medium text-sm transition-all duration-200',
                                        'bg-amber-600 text-white shadow-lg hover:bg-amber-700 border-2 border-amber-600' =>
                                            $checkoutMode === 'service',
                                        'bg-white text-gray-700 border-2 border-gray-200 hover:border-gray-300 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700 dark:hover:border-neutral-600' =>
                                            $checkoutMode !== 'service',
                                    ])>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Service</span>
                                </button>
                            </div>
                        </div>

                        {{-- Service Order Dropdown (conditional) --}}
                        @if ($checkoutMode === 'service')
                            <div class="space-y-2 md:col-span-2 animate-in fade-in duration-300">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200">
                                    <span class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                        Service Order
                                    </span>
                                </label>

                                <div class="relative">
                                    <select wire:model.change="serviceOrderId"
                                        class="block w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 bg-white text-sm text-gray-800
                                               placeholder-gray-500 shadow-sm transition-colors duration-200
                                               focus:border-amber-500 focus:outline-none focus:ring-0
                                               dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100
                                               dark:placeholder-neutral-500 dark:focus:border-amber-500">
                                        <option value="">— Pilih Service Order —</option>
                                        @foreach ($this->serviceOrderOptions as $id => $number)
                                            <option value="{{ $id }}">{{ $number }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div
                                    class="inline-flex items-start gap-2 rounded-lg bg-amber-50 p-3 dark:bg-amber-900/20">
                                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-amber-600 dark:text-amber-400"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 8a6 6 0 06-12 0 6 6 0 0112 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V4a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-xs font-medium text-amber-800 dark:text-amber-200">
                                        Setelah memilih Service Order, item part & jasa akan otomatis ditambahkan ke
                                        keranjang transaksi.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        {{-- Pilih Customer --}}
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.856-1.488M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z">
                                        </path>
                                    </svg>
                                    Pelanggan
                                </span>
                            </label>

                            <div class="relative">
                                <select wire:model="customerId"
                                    class="block w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 bg-white text-sm text-gray-800
                                           placeholder-gray-500 shadow-sm transition-colors duration-200
                                           focus:border-sky-500 focus:outline-none focus:ring-0
                                           dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100
                                           dark:placeholder-neutral-500 dark:focus:border-sky-500">
                                    <option value="">Walk-in / Umum</option>
                                    @foreach ($customerOptions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM6 6h.01M6 10h.01M6 14h.01M6 18h.01">
                                        </path>
                                    </svg>
                                    Metode Pembayaran
                                </span>
                            </label>

                            <div class="relative">
                                <select wire:model.change="paymentId"
                                    class="block w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 bg-white text-sm text-gray-800
                                           placeholder-gray-500 shadow-sm transition-colors duration-200
                                           focus:border-sky-500 focus:outline-none focus:ring-0
                                           dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100
                                           dark:placeholder-neutral-500 dark:focus:border-sky-500">
                                    <option value="">Pilih metode</option>
                                    @foreach ($paymentOptions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        {{-- Jenis Diskon Item --}}
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    Jenis Diskon
                                </span>
                            </label>

                            <div class="relative">
                                <select wire:model.change="selectedDiscountTypeId"
                                    class="block w-full px-4 py-2.5 rounded-lg border-2 transition-colors duration-200 focus:outline-none focus:ring-0 bg-white text-sm text-gray-800 placeholder-gray-500 shadow-sm
                                           dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder-neutral-500
                                           @if ($selectedDiscountTypeId) border-green-500 focus:border-green-600 dark:border-green-500 dark:focus:border-green-600 @else border-gray-200 focus:border-sky-500 dark:border-neutral-700 dark:focus:border-sky-500 @endif">
                                    <option value="">Tanpa Diskon</option>
                                    @foreach ($discountTypeOptions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="h-5 w-5 transition-colors duration-200 @if ($selectedDiscountTypeId) text-green-600 dark:text-green-300 @else text-gray-600 dark:text-neutral-400 @endif"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Diskon Universal Section --}}
                    @if ($universalDiscountMode)
                        <div
                            class="mt-4 animate-in fade-in duration-300 rounded-lg border-2 border-purple-200 bg-purple-50 p-4 dark:border-purple-900/40 dark:bg-purple-900/20">
                            <label
                                class="mb-3 block text-xs font-bold uppercase tracking-wide text-purple-900 dark:text-purple-200">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Diskon Universal (Keseluruhan)
                                </span>
                            </label>

                            <div class="flex gap-3">
                                <div class="w-28">
                                    <select wire:model="universalDiscountMode"
                                        class="block w-full px-3 py-2 rounded-lg border-2 border-purple-300 bg-white text-sm font-medium text-gray-800
                                               focus:border-purple-500 focus:outline-none focus:ring-0
                                               dark:border-purple-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-purple-400">
                                        <option value="">-</option>
                                        <option value="percent">Persen %</option>
                                        <option value="amount">Nominal Rp</option>
                                    </select>
                                </div>

                                <div class="flex-1">
                                    <input type="number" step="0.01" min="0"
                                        wire:model.lazy="universalDiscountValue"
                                        class="block w-full px-4 py-2 rounded-lg border-2 border-purple-300 bg-white text-right text-sm text-gray-800
                                               placeholder-gray-500 shadow-sm transition-colors duration-200
                                               focus:border-purple-500 focus:outline-none focus:ring-0
                                               dark:border-purple-600 dark:bg-neutral-800 dark:text-neutral-100
                                               dark:placeholder-neutral-500 dark:focus:border-purple-400"
                                        placeholder="0" />
                                </div>

                                <button type="button" wire:click="$set('universalDiscountMode', null)"
                                    class="inline-flex items-center justify-center rounded-lg border-2 border-purple-300 bg-white px-3 py-2 text-purple-600 hover:bg-purple-50 transition-colors duration-200 dark:border-purple-600 dark:bg-neutral-800 dark:text-purple-400 dark:hover:bg-neutral-700/50">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>

                            <p class="mt-2 text-xs font-medium text-purple-700 dark:text-purple-300">
                                Berlaku ke total setelah diskon per-item
                            </p>
                        </div>
                    @else
                        <div class="mt-4">
                            <button type="button" wire:click="$set('universalDiscountMode', 'percent')"
                                class="inline-flex items-center gap-2 rounded-lg border-2 border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:border-purple-400 hover:bg-purple-50 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:border-purple-500 dark:hover:bg-neutral-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Diskon Universal
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 🔧 Service Order Info Card --}}
            @if ($checkoutMode === 'service' && $this->selectedServiceOrder)
                @php $so = $this->selectedServiceOrder; @endphp
                <div class="col-span-12">
                    <div
                        class="rounded-2xl border-2 border-amber-200/60 bg-white p-4 md:p-5 shadow-lg dark:border-neutral-700 dark:bg-neutral-900">
                        {{-- Header --}}
                        <div class="mb-4 flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="text-sm font-bold uppercase tracking-wide text-gray-800 dark:text-neutral-100">
                                    Info Service Order
                                </h3>
                                <p class="text-[11px] text-gray-500 dark:text-neutral-400">
                                    No. SO: <span
                                        class="font-semibold text-amber-700 dark:text-amber-300">{{ $so->number }}</span>
                                    <span class="px-1 text-gray-400">•</span>
                                    Status:
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase
                                        @switch($so->status)
                                            @case('checkin') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 @break
                                            @case('in_progress') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 @break
                                            @case('ready') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 @break
                                            @default bg-gray-100 text-gray-700 dark:bg-neutral-700 dark:text-neutral-300
                                        @endswitch
                                    ">
                                        {{ str_replace('_', ' ', $so->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- Content Grid: Customer | Kendaraan | Mekanik --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            {{-- Customer --}}
                            <div
                                class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-neutral-700 dark:bg-neutral-800">
                                <div class="mb-2 flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-500 dark:text-neutral-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span
                                        class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-neutral-400">Pelanggan</span>
                                </div>
                                @php
                                    $cust = $so->customerSnapshot ?? $so->customer;
                                @endphp
                                @if ($cust)
                                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">
                                        {{ $cust->name ?? '-' }}</p>
                                    @if ($cust->phone ?? null)
                                        <p class="mt-0.5 text-xs text-gray-500 dark:text-neutral-400">
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ $cust->phone }}
                                            </span>
                                        </p>
                                    @endif
                                    @if ($cust->address ?? null)
                                        <p class="mt-0.5 text-xs text-gray-500 dark:text-neutral-400 line-clamp-2">
                                            {{ $cust->address }}</p>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-400 italic dark:text-neutral-500">Tidak ada data
                                        pelanggan</p>
                                @endif
                            </div>

                            {{-- Kendaraan --}}
                            <div
                                class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-neutral-700 dark:bg-neutral-800">
                                <div class="mb-2 flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-500 dark:text-neutral-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h8m-8 4h8m-4 4h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                    </svg>
                                    <span
                                        class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-neutral-400">Kendaraan</span>
                                </div>
                                @forelse ($so->units as $unit)
                                    <div
                                        class="@if (!$loop->first) mt-2 border-t border-gray-200 pt-2 dark:border-neutral-700 @endif">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">
                                            {{ $unit->brand }} {{ $unit->model }}
                                            @if ($unit->year)
                                                <span
                                                    class="text-xs text-gray-500 dark:text-neutral-400">({{ $unit->year }})</span>
                                            @endif
                                        </p>
                                        <div class="mt-0.5 flex flex-wrap items-center gap-2">
                                            @if ($unit->plate_number)
                                                <span
                                                    class="inline-flex items-center rounded-md bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                    {{ $unit->plate_number }}
                                                </span>
                                            @endif
                                            @if ($unit->color)
                                                <span
                                                    class="text-xs text-gray-500 dark:text-neutral-400">{{ $unit->color }}</span>
                                            @endif
                                        </div>
                                        @if ($unit->complaint)
                                            <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400 line-clamp-2">
                                                <span class="font-medium">Keluhan:</span> {{ $unit->complaint }}
                                            </p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-400 italic dark:text-neutral-500">Tidak ada data
                                        kendaraan</p>
                                @endforelse
                            </div>

                            {{-- Mekanik --}}
                            <div
                                class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-neutral-700 dark:bg-neutral-800">
                                <div class="mb-2 flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-500 dark:text-neutral-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span
                                        class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-neutral-400">Mekanik</span>
                                </div>
                                @php
                                    $allMechanics = $so->units->flatMap(fn($u) => $u->mechanics)->unique('id');
                                @endphp
                                @forelse ($allMechanics as $mechanic)
                                    <div
                                        class="@if (!$loop->first) mt-1.5 @endif flex items-center gap-2">
                                        <div
                                            class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-100 text-xs font-bold text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">
                                            {{ strtoupper(substr($mechanic->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">
                                                {{ $mechanic->name }}</p>
                                            @if ($mechanic->pivot->role ?? null)
                                                <p class="text-[10px] text-gray-500 dark:text-neutral-400 uppercase">
                                                    {{ $mechanic->pivot->role }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-400 italic dark:text-neutral-500">Belum ada mekanik
                                        ditugaskan</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-span-12 flex flex-col md:flex-col lg:flex-row gap-8">
                <div class="grid grid-cols-1 min-w-full gap-8">
                    <!-- Table Section -->
                    <div class="">
                        <!-- Card -->
                        <div class="flex flex-col">
                            <div class="mb-3 flex items-center justify-between">
                                <h3
                                    class="text-sm font-bold uppercase tracking-wide text-gray-800 dark:text-neutral-100 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Keranjang Belanja
                                </h3>
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-sky-200 px-3 py-1 text-xs font-bold text-sky-900 shadow-md dark:bg-sky-900/40 dark:text-sky-200">
                                    {{ count($this->carts) }} item
                                </span>
                            </div>
                            <div class="-m-1.5 overflow-x-auto">
                                <div class="p-1.5 min-w-full inline-block align-middle">
                                    <div
                                        class="bg-white border-2 border-sky-200/60 rounded-xl shadow-xl overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                                        <!-- Table -->
                                        <table class="min-w-full divide-y divide-sky-200/40 dark:divide-neutral-700">
                                            <thead class="bg-sky-600 dark:bg-neutral-800">
                                                <tr>
                                                    <th class="px-3 py-3 md:px-6 md:py-4 text-start">
                                                        <span
                                                            class="text-xs font-bold uppercase tracking-wider text-white dark:text-neutral-100">
                                                            Produk
                                                        </span>
                                                    </th>
                                                    <th class="px-3 py-3 md:px-6 md:py-4 text-center">
                                                        <span
                                                            class="text-xs font-bold uppercase tracking-wider text-white dark:text-neutral-100">
                                                            Qty
                                                        </span>
                                                    </th>
                                                    <th class="px-3 py-3 md:px-6 md:py-4 text-end">
                                                        <span
                                                            class="text-xs font-bold uppercase tracking-wider text-white dark:text-neutral-100">
                                                            Harga
                                                        </span>
                                                    </th>
                                                    <th class="px-3 py-3 md:px-6 md:py-4 text-end">
                                                        <span
                                                            class="text-xs font-bold uppercase tracking-wider text-white dark:text-neutral-100">
                                                            Diskon
                                                        </span>
                                                    </th>
                                                    <th class="px-3 py-3 md:px-6 md:py-4 text-end">
                                                        <span
                                                            class="text-xs font-bold uppercase tracking-wider text-white dark:text-neutral-100">
                                                            Setelah Diskon
                                                        </span>
                                                    </th>
                                                    <th class="px-3 py-3 md:px-6 md:py-4 text-end">
                                                        <span
                                                            class="text-xs font-bold uppercase tracking-wider text-white dark:text-neutral-100">
                                                            Total
                                                        </span>
                                                    </th>
                                                    <th class="px-3 py-3 md:px-6 md:py-4 text-center">
                                                        <span
                                                            class="text-xs font-bold uppercase tracking-wider text-white dark:text-neutral-100">
                                                            Aksi
                                                        </span>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody
                                                class="divide-y divide-sky-200/40 dark:divide-neutral-700 [&>tr:nth-child(odd)]:bg-sky-50/40 [&>tr:nth-child(odd)]:dark:bg-neutral-800/50">
                                                @forelse ($this->carts as $index => $cart)
                                                    @php
                                                        $unitPrice = $cart['selling_price'] ?? 0;
                                                        $finalPrice = $cart['final_unit_price'] ?? $unitPrice;
                                                        $qty = $cart['quantity'] ?? 0;
                                                        $lineTotal = $qty * $finalPrice;
                                                        $lineDiscAmt = $cart['discount_amount'] ?? 0;
                                                    @endphp
                                                    <tr
                                                        class="transition-colors duration-200 hover:bg-sky-100/60 dark:hover:bg-neutral-800/80">
                                                        {{-- Product --}}
                                                        <td class="px-3 py-3 md:px-6 md:py-4">
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
                                                        <td class="px-3 py-3 md:px-6 md:py-4">
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
                                                        <td class="px-3 py-3 md:px-6 md:py-4 text-end">
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
                                                        <td class="px-3 py-3 md:px-6 md:py-4 text-end">
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
                                                        <td class="px-3 py-3 md:px-6 md:py-4 text-end">
                                                            <span
                                                                class="text-sm font-medium text-gray-900 dark:text-neutral-100">
                                                                Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                                            </span>
                                                        </td>

                                                        {{-- Total line (qty x finalUnitPrice) --}}
                                                        <td class="px-3 py-3 md:px-6 md:py-4 text-end">
                                                            <span
                                                                class="text-sm font-semibold text-gray-900 dark:text-neutral-100">
                                                                Rp {{ number_format($lineTotal, 0, ',', '.') }}
                                                            </span>
                                                        </td>

                                                        {{-- Aksi: hapus diskon & hapus item --}}
                                                        <td class="px-3 py-3 md:px-6 md:py-4 text-center">
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
                                                        <td colspan="7" class="px-6 py-12 text-center">
                                                            <div class="flex flex-col items-center gap-2">
                                                                <svg class="h-12 w-12 text-gray-300 dark:text-neutral-600"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="1.5"
                                                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                                                    </path>
                                                                </svg>
                                                                <p
                                                                    class="text-sm font-medium text-gray-500 dark:text-neutral-400">
                                                                    Keranjang masih kosong</p>
                                                                <p class="text-xs text-gray-400 dark:text-neutral-500">
                                                                    Mulai dengan menambahkan produk dari tab Produk</p>
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
                                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
                                        <div
                                            class="w-full max-w-4xl mx-4 h-[90vh] bg-white dark:bg-neutral-900 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-neutral-700">
                                            <div
                                                class="flex flex-wrap items-center justify-between gap-2 p-3 border-b border-gray-100 dark:border-neutral-800 bg-gray-50 dark:bg-neutral-800">
                                                <div class="flex items-center gap-3">
                                                    <div class="rounded-md bg-sky-100 dark:bg-sky-900/30 p-2">
                                                        <x-heroicon-o-printer
                                                            class="h-5 w-5 text-sky-600 dark:text-sky-300" />
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="font-semibold text-sm text-gray-900 dark:text-neutral-100">
                                                            Preview Struk</div>
                                                        <div class="text-xs text-gray-500 dark:text-neutral-400">Struk
                                                            transaksi — bisa dicetak atau dibuka di tab baru</div>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <button x-bind:disabled="loading"
                                                        @click="if($refs.ifr && $refs.ifr.contentWindow) { $refs.ifr.contentWindow.focus(); $refs.ifr.contentWindow.print(); }"
                                                        class="inline-flex items-center gap-2 px-3 py-1 rounded bg-sky-600 text-white text-sm disabled:opacity-60">
                                                        <x-heroicon-o-printer class="h-4 w-4" />
                                                        <span>Print</span>
                                                    </button>

                                                    <a :href="src" target="_blank" rel="noopener"
                                                        class="inline-flex items-center gap-2 px-3 py-1 rounded border text-sm">
                                                        <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                                                        <span>Buka di tab baru</span>
                                                    </a>

                                                    <button @click="open=false; src='';"
                                                        class="px-3 py-1 rounded border text-sm">Tutup</button>
                                                </div>
                                            </div>

                                            <div class="relative h-full bg-white dark:bg-neutral-900">
                                                <!-- loading overlay -->
                                                <div x-show="loading"
                                                    class="absolute inset-0 z-40 flex items-center justify-center bg-white/70 dark:bg-neutral-900/70">
                                                    <div class="flex flex-col items-center gap-2">
                                                        <svg class="animate-spin h-8 w-8 text-sky-600"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4">
                                                            </circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                        </svg>
                                                        <div class="text-sm text-gray-700 dark:text-neutral-300">Memuat
                                                            struk...</div>
                                                    </div>
                                                </div>

                                                <iframe x-ref="ifr" :src="src" class="w-full h-full"
                                                    frameborder="0" @load="loading=false"></iframe>
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

            <div class="col-span-12 grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 lg:gap-8">
                {{-- Summary --}}
                <div
                    class="rounded-2xl border-2 border-sky-200/60 bg-white p-4 md:p-6 shadow-xl
               dark:border-neutral-800 dark:bg-neutral-900">
                    <h3
                        class="mb-5 text-sm font-bold uppercase tracking-wide text-gray-800 dark:text-neutral-100 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Ringkasan Total
                    </h3>

                    <dl class="space-y-3">
                        <div
                            class="flex items-center justify-between rounded-lg bg-white/60 px-4 py-2.5 dark:bg-neutral-800/40">
                            <dt class="text-sm font-medium text-gray-600 dark:text-neutral-300">Subtotal</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-neutral-100">
                                Rp {{ number_format($this->subtotal, 0, ',', '.') }}
                            </dd>
                        </div>

                        @if ($this->itemDiscountTotal > 0)
                            <div
                                class="flex items-center justify-between rounded-lg bg-red-50/60 px-4 py-2.5 dark:bg-red-900/20">
                                <dt class="text-sm font-medium text-red-600 dark:text-red-400 flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Diskon Item
                                </dt>
                                <dd class="text-sm font-semibold text-red-600 dark:text-red-400">
                                    − Rp {{ number_format($this->itemDiscountTotal, 0, ',', '.') }}
                                </dd>
                            </div>
                        @endif

                        <div
                            class="flex items-center justify-between rounded-lg bg-white/60 px-4 py-2.5 dark:bg-neutral-800/40">
                            <dt class="text-sm font-medium text-gray-600 dark:text-neutral-300">Setelah Diskon Item
                            </dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-neutral-100">
                                Rp {{ number_format($this->subtotalAfterItemDiscount, 0, ',', '.') }}
                            </dd>
                        </div>

                        @if ($this->universalDiscountAmount > 0)
                            <div
                                class="flex items-center justify-between rounded-lg bg-purple-50/60 px-4 py-2.5 dark:bg-purple-900/20">
                                <dt
                                    class="text-sm font-medium text-purple-600 dark:text-purple-400 flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Diskon Universal
                                </dt>
                                <dd class="text-sm font-semibold text-purple-600 dark:text-purple-400">
                                    − Rp {{ number_format($this->universalDiscountAmount, 0, ',', '.') }}
                                </dd>
                            </div>
                        @endif

                        <div
                            class="mt-4 flex items-center justify-between rounded-xl border-2 border-sky-300 bg-sky-50 px-5 py-4 dark:border-sky-800 dark:bg-sky-950/30">
                            <dt class="text-sm font-bold uppercase tracking-wider text-sky-900 dark:text-sky-100">
                                Grand Total
                            </dt>
                            <dd class="text-2xl font-bold text-sky-600 dark:text-sky-400">
                                Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Pembayaran --}}
                <div
                    class="rounded-2xl border-2 border-emerald-200/60 bg-white p-4 md:p-6 shadow-xl
               dark:border-neutral-800 dark:bg-neutral-900">
                    <h3
                        class="mb-5 text-sm font-bold uppercase tracking-wide text-gray-800 dark:text-neutral-100 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM6 6h.01M6 10h.01M6 14h.01M6 18h.01">
                            </path>
                        </svg>
                        Pembayaran
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200">
                                Status Pembayaran
                            </label>

                            <select wire:model.lazy="paymentStatus"
                                class="block w-full rounded-lg border-2 border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-sm transition-colors duration-200
                                       focus:border-emerald-500 focus:outline-none focus:ring-0
                                       dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-emerald-500">
                                <option value="unpaid">Belum Dibayar</option>
                                <option value="partial">Sebagian</option>
                                <option value="paid">Lunas</option>
                                <option value="refunded">Dikembalikan</option>
                            </select>

                            <p
                                class="mt-2 text-xs font-medium text-gray-500 dark:text-neutral-400 flex items-start gap-2">
                                <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-blue-500" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 8a6 6 0 06-12 0 6 6 0 0112 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V4a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Jika pelanggan membayar tunai, masukkan jumlah yang diterima di bawah.
                            </p>
                        </div>

                        @if ($paymentStatus !== 'unpaid')
                            <div
                                class="animate-in fade-in duration-300 space-y-4 rounded-xl bg-white/70 p-4 dark:bg-neutral-800/50">
                                <div>
                                    <label
                                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200 flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Uang Diterima
                                    </label>
                                    <input type="number" min="0" step="0.01" wire:model.lazy="amountPaid"
                                        class="block w-full rounded-lg border-2 border-emerald-200 bg-white px-4 py-2.5 text-right text-sm text-gray-800 shadow-sm transition-colors duration-200
                                               placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-0
                                               dark:border-emerald-800/50 dark:bg-neutral-700/50 dark:text-neutral-100 dark:placeholder-neutral-500 dark:focus:border-emerald-500"
                                        placeholder="0" />
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-lg bg-red-50/60 p-3 dark:bg-red-900/20">
                                        <p class="text-xs font-medium text-red-600 dark:text-red-400">Sisa Bayar</p>
                                        <p class="text-lg font-bold text-red-700 dark:text-red-300">
                                            Rp
                                            {{ number_format(max(0, $this->grandTotal - $this->amountPaid), 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-green-50/60 p-3 dark:bg-green-900/20">
                                        <p class="text-xs font-medium text-green-600 dark:text-green-400">Kembalian</p>
                                        <p class="text-lg font-bold text-green-700 dark:text-green-300">
                                            Rp {{ number_format($this->changeAmount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <button type="button" wire:click="resetCart"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-sky-300/60 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-sky-50/60 hover:border-sky-400 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700/50 dark:hover:border-neutral-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </button>

                        <button type="button" wire:click="checkout"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-500 px-4 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-emerald-600 hover:shadow-xl active:scale-95">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan & Bayar
                        </button>
                    </div>
                </div>

            </div>
        @endif

        @if ($activeTab === 'products')
            {{-- Search Section --}}
            <div class="col-span-12">
                <div
                    class="rounded-2xl border-2 border-sky-200/60 bg-white p-4 md:p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="space-y-4">
                        <h4
                            class="text-sm font-bold uppercase tracking-wide text-gray-800 dark:text-neutral-100 flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Pencarian & Filter
                        </h4>

                        {{-- Search Bar --}}
                        <div class="relative">
                            <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2">
                                <svg class="h-5 w-5 text-gray-500 dark:text-neutral-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" wire:model.live.debounce.500ms="search"
                                class="block w-full rounded-lg border-2 border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-800 shadow-sm transition-colors duration-200
                                       placeholder-gray-500 focus:border-sky-500 focus:outline-none focus:ring-0
                                       dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder-neutral-500 dark:focus:border-sky-500"
                                placeholder="Cari nama barang, keyword, SKU..." />
                        </div>

                        {{-- Filters Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Category Filter --}}
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200 flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                        </path>
                                    </svg>
                                    Kategori
                                </label>
                                <div class="relative">
                                    <select wire:model.change="productCategoryId"
                                        class="block w-full rounded-lg border-2 border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-sm transition-colors duration-200
                                               focus:border-sky-500 focus:outline-none focus:ring-0
                                               dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-sky-500">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($this->productCategories as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            {{-- Brand Filter --}}
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-700 dark:text-neutral-200 flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    Merk/Brand
                                </label>
                                <div class="relative">
                                    <select wire:model.change="brandId"
                                        class="block w-full rounded-lg border-2 border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-sm transition-colors duration-200
                                               focus:border-sky-500 focus:outline-none focus:ring-0
                                               dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-sky-500">
                                        <option value="">Semua Merk</option>
                                        @foreach ($this->brands as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 flex flex-col md:flex-col lg:flex-row gap-8">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 min-w-full gap-4 lg:gap-6">
                    @foreach ($this->products as $product)
                        @php
                            $images = $product->product->getMedia('productImages');
                        @endphp

                        <div
                            class="group flex flex-col rounded-2xl border-2 border-sky-200/60 bg-white shadow-lg transition-all duration-300 hover:shadow-2xl hover:border-sky-400 hover:-translate-y-1 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-sky-600 overflow-hidden">
                            {{-- Image Section --}}
                            <div class="relative p-4 bg-gray-50 dark:bg-neutral-800">
                                <div class="rounded-xl bg-white dark:bg-neutral-800 p-3 shadow-sm">
                                    @if ($images->isEmpty())
                                        <div
                                            class="aspect-square flex items-center justify-center rounded-xl text-sm text-neutral-400 bg-neutral-200 dark:bg-neutral-700 font-medium">
                                            <svg class="h-12 w-12 text-neutral-300 dark:text-neutral-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="relative overflow-hidden rounded-xl bg-neutral-200 dark:bg-neutral-700"
                                            data-product-carousel>
                                            <div class="relative aspect-square">
                                                @foreach ($images as $index => $media)
                                                    <img data-carousel-image data-index="{{ $index }}"
                                                        src="{{ $media->getUrl() }}"
                                                        alt="Foto produk {{ $product->product->name }} - {{ $index + 1 }}"
                                                        class="absolute inset-0 h-full w-full object-cover transition-opacity duration-300 ease-out {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
                                                        loading="lazy">
                                                @endforeach
                                            </div>

                                            @if ($images->count() > 1)
                                                <button type="button" data-carousel-prev
                                                    class="absolute left-2 top-1/2 -translate-y-1/2 inline-flex h-8 w-8 items-center justify-center rounded-full bg-sky-600/80 text-white text-sm shadow-md backdrop-blur-sm hover:bg-sky-700 transition-all duration-200 z-10">
                                                    ‹
                                                </button>

                                                <button type="button" data-carousel-next
                                                    class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-8 w-8 items-center justify-center rounded-full bg-sky-600/80 text-white text-sm shadow-md backdrop-blur-sm hover:bg-sky-700 transition-all duration-200 z-10">
                                                    ›
                                                </button>

                                                <div
                                                    class="absolute inset-x-0 bottom-3 flex items-center justify-center gap-1.5">
                                                    @foreach ($images as $index => $media)
                                                        <button type="button" data-carousel-dot
                                                            data-index="{{ $index }}"
                                                            class="h-1.5 rounded-full transition-all duration-200 {{ $index === 0 ? 'bg-white/90 w-6' : 'bg-white/40 w-3' }}"></button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Product Details Section --}}
                            <div class="flex-1 flex flex-col p-4 space-y-3">
                                {{-- Product Title & Stock --}}
                                <div class="space-y-2">
                                    <h3 class="text-sm font-bold line-clamp-2 text-gray-900 dark:text-neutral-100">
                                        {{ $product->product->productCategory->item_type == 'part' ? $product->product->brand->name . ' | ' : '' }}
                                        {{ $product->product->name }}
                                    </h3>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m0 0l8 4m0 0l8-4m0 0v10l-8 4m0 0l-8-4m0 0v10l8 4m0 0l8-4">
                                            </path>
                                        </svg>
                                        <span
                                            class="text-xs font-bold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Stok:
                                            {{ $product->quantity }}</span>
                                    </div>
                                </div>

                                {{-- Product Details --}}
                                <div class="space-y-2 border-t border-sky-200/60 dark:border-neutral-700/50 pt-3">
                                    <div class="rounded-lg bg-sky-50/60 px-3 py-2.5 dark:bg-sky-900/20">
                                        <p class="text-xs font-medium text-gray-600 dark:text-neutral-400">Harga Jual
                                        </p>
                                        <p class="text-lg font-bold text-sky-700 dark:text-sky-300">Rp
                                            {{ number_format($product->productPrice->selling_price ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-sky-50/80 px-3 py-2 dark:bg-neutral-800/40">
                                        <div class="text-xs font-medium text-gray-700 dark:text-neutral-300 truncate">
                                            {{ $product->product->keyword }}</div>
                                    </div>

                                    <div class="rounded-lg bg-sky-50/80 px-3 py-2 dark:bg-neutral-800/40">
                                        <div class="text-[11px] text-gray-600 dark:text-neutral-400">Kompatibilitas
                                        </div>
                                        <div class="text-xs font-medium text-gray-700 dark:text-neutral-300 truncate">
                                            {{ $product->product->compatibility }}</div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-2 text-[11px]">
                                        <div class="rounded-lg bg-sky-50/80 px-2 py-2 dark:bg-neutral-800/40">
                                            <span class="text-gray-600 dark:text-neutral-400">Tipe</span>
                                            <div class="font-semibold text-gray-700 dark:text-neutral-300">
                                                {{ substr($product->product->type, 0, 10) }}</div>
                                        </div>
                                        <div class="rounded-lg bg-sky-50/80 px-2 py-2 dark:bg-neutral-800/40">
                                            <span class="text-gray-600 dark:text-neutral-400">Ukuran</span>
                                            <div class="font-semibold text-gray-700 dark:text-neutral-300">
                                                {{ substr($product->product->size, 0, 10) }}</div>
                                        </div>
                                        <div class="rounded-lg bg-amber-50/60 px-2 py-2 dark:bg-amber-900/20">
                                            <span class="text-amber-600 dark:text-amber-300 text-[10px]"
                                                title="{{ $product->product->sku }}">SKU</span>
                                            <div
                                                class="font-semibold text-amber-700 dark:text-amber-300 text-[10px] truncate">
                                                {{ substr($product->product->sku, 0, 8) }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Add to Cart Button --}}
                                <div
                                    class="mt-auto space-y-2 border-t border-sky-200/60 dark:border-neutral-700/50 pt-3">
                                    <button type="button" @click="$wire.addToCart('{{ $product->id }}')"
                                        class="w-full py-3 px-4 inline-flex items-center justify-center gap-2 text-sm font-bold rounded-lg border-0 bg-amber-500 text-white shadow-lg transition-all duration-200 hover:bg-amber-600 hover:shadow-xl active:scale-95">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.5v15m7.5-7.5h-15"></path>
                                        </svg>
                                        Masukkan Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div
                class="col-span-12 flex items-center justify-center rounded-2xl border-2 border-sky-200/60 bg-white p-3 md:p-4 shadow-xl dark:border-neutral-800 dark:bg-neutral-900">
                {{ $this->products->links('vendor.livewire.simple-tailwind') }}
            </div>
        @endif
    </div>


</x-filament-panels::page>
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
