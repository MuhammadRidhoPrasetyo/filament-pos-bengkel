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

                    <div class="grid gap-3 md:grid-cols-3 mb-3">
                        <div class="space-y-1">
                            <label class="block text-[11px] font-semibold text-gray-600 dark:text-neutral-300">
                                Mode Transaksi
                            </label>
                            <select wire:model.change="checkoutMode"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-xs text-gray-800 shadow-sm
                   focus:border-sky-400 focus:ring-0
                   dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-sky-500">
                                <option value="normal">Transaksi Biasa</option>
                                <option value="service">Dari Service Order</option>
                            </select>
                        </div>

                        @if ($checkoutMode === 'service')
                            <div class="space-y-1 md:col-span-2">
                                <label class="block text-[11px] font-semibold text-gray-600 dark:text-neutral-300">
                                    Service Order
                                </label>
                                <select wire:model.change="serviceOrderId"
                                    class="block w-full rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-xs text-gray-800 shadow-sm
                       focus:border-sky-400 focus:ring-0
                       dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-sky-500">
                                    <option value="">Pilih Service Order</option>
                                    @foreach ($serviceOrderOptions as $id => $number)
                                        <option value="{{ $id }}">{{ $number }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-[10px] text-gray-400">
                                    Setelah memilih Service Order, item part & jasa akan otomatis masuk ke keranjang.
                                </p>
                            </div>
                        @endif
                    </div>

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
                            <select wire:model.change="paymentId"
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
                                            class="w-[880px] max-w-[95%] h-[90vh] bg-white dark:bg-neutral-900 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-neutral-700">
                                            <div
                                                class="flex items-center justify-between p-3 border-b border-gray-100 dark:border-neutral-800 bg-gray-50 dark:bg-neutral-800">
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
                                Status Pembayaran
                            </label>

                            <select wire:model.lazy="paymentStatus"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-xs text-gray-800 shadow-sm
                   focus:border-sky-400 focus:ring-0 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                <option value="unpaid">Belum Dibayar</option>
                                <option value="partial">Sebagian</option>
                                <option value="paid">Lunas</option>
                                <option value="refunded">Dikembalikan</option>
                            </select>

                            <p class="mt-2 text-xs text-gray-500">Jika pelanggan membayar tunai, pilih metode
                                pembayaran dan masukkan jumlah yang diterima di bawah.</p>


                            @if ($paymentStatus !== 'unpaid')
                                <div class="mt-3">
                                    <label
                                        class="mb-1 block text-xs font-semibold text-gray-600 dark:text-neutral-300">Uang
                                        Diterima</label>
                                    <input type="number" min="0" step="0.01" wire:model.lazy="amountPaid"
                                        class="block w-full rounded-lg border-gray-200 px-3 py-2 text-right text-sm text-gray-800
                           focus:border-gray-400 focus:ring-0
                           dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-neutral-500"
                                        placeholder="0" />

                                    <div class="mt-2 flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-neutral-300">Sisa Bayar</span>
                                        <span class="font-semibold text-gray-900 dark:text-neutral-100">Rp
                                            {{ number_format(max(0, $this->grandTotal - $this->amountPaid), 0, ',', '.') }}</span>
                                    </div>

                                    <div class="mt-1 flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-neutral-300">Kembalian</span>
                                        <span class="font-semibold text-gray-900 dark:text-neutral-100">Rp
                                            {{ number_format($this->changeAmount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
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
                        @php
                            $images = $product->product->getMedia('productImages');
                        @endphp

                        <div class="group flex flex-col">
                            <div class="relative">
                                <div class="rounded-3xl bg-white dark:bg-neutral-800 p-4 shadow-sm">
                                    @if ($images->isEmpty())
                                        <div
                                            class="aspect-square flex items-center justify-center rounded-2xl text-sm text-neutral-400 bg-neutral-100 dark:bg-neutral-900">
                                            Tidak ada gambar
                                        </div>
                                    @else
                                        <div class="relative overflow-hidden rounded-2xl bg-neutral-100 dark:bg-neutral-900"
                                            data-product-carousel>
                                            <div class="relative aspect-square">
                                                @foreach ($images as $index => $media)
                                                    <img data-carousel-image data-index="{{ $index }}"
                                                        src="{{ $media->getUrl() }}"
                                                        alt="Foto produk {{ $product->product->name }} - {{ $index + 1 }}"
                                                        class="absolute inset-0 h-full w-full object-cover transition-opacity duration-300 ease-out
                    {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
                                                        loading="lazy">
                                                @endforeach
                                            </div>

                                            @if ($images->count() > 1)
                                                <button type="button" data-carousel-prev
                                                    class="absolute left-2 top-1/2 -translate-y-1/2 inline-flex h-8 w-8 items-center justify-center rounded-full bg-black/50 text-white text-sm shadow-md backdrop-blur-sm hover:bg-black/70">
                                                    ‹
                                                </button>

                                                <button type="button" data-carousel-next
                                                    class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-8 w-8 items-center justify-center rounded-full bg-black/50 text-white text-sm shadow-md backdrop-blur-sm hover:bg-black/70">
                                                    ›
                                                </button>

                                                <div
                                                    class="absolute inset-x-0 bottom-3 flex items-center justify-center gap-1.5">
                                                    @foreach ($images as $index => $media)
                                                        <button type="button" data-carousel-dot
                                                            data-index="{{ $index }}"
                                                            class="h-1.5 rounded-full transition-all duration-200
                        {{ $index === 0 ? 'bg-white/90 w-6' : 'bg-white/40 w-3' }}"></button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-6">
                                    <h3 class="text-base md:text-lg font-semibold line-clamp-2">
                                        {{ $product->product->productCategory->item_type == 'part' ? $product->product->brand->name . ' | ' : '' }}
                                        {{ $product->product->name }}
                                    </h3>
                                    <p class="mt-2 text-sm md:text-base font-semibold">
                                        Stok : {{ $product->quantity }}
                                    </p>
                                </div>

                                {{-- JANGAN pakai absolute inset-0 lagi di sini, kalau mau seluruh card bisa di-klik, bungkus saja seluruh card dengan <a> --}}
                                {{-- Contoh (opsional): --}}
                                {{-- <a href="{{ route('produk.show', $product->id) }}" class="absolute inset-0 z-0"></a> --}}
                            </div>

                            <div class="mt-6 text-sm space-y-2">
                                <div class="border-t border-neutral-200 dark:border-neutral-700/70 py-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        <span class="font-medium">Harga</span>
                                        <span class="text-right font-semibold">
                                            {{ number_format($product->productPrice->selling_price ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="border-t border-neutral-200 dark:border-neutral-700/70 py-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        <span class="font-medium">Keyword</span>
                                        <span class="text-right">{{ $product->product->keyword }}</span>
                                    </div>
                                </div>
                                <div class="border-t border-neutral-200 dark:border-neutral-700/70 py-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        <span class="font-medium">Bisa digunakan untuk</span>
                                        <span class="text-right">{{ $product->product->compatibility }}</span>
                                    </div>
                                </div>
                                <div class="border-t border-neutral-200 dark:border-neutral-700/70 py-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        <span class="font-medium">Tipe</span>
                                        <span class="text-right">{{ $product->product->type }}</span>
                                    </div>
                                </div>
                                <div class="border-t border-neutral-200 dark:border-neutral-700/70 py-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        <span class="font-medium">Ukuran</span>
                                        <span class="text-right">{{ $product->product->size }}</span>
                                    </div>
                                </div>
                                <div class="border-t border-neutral-200 dark:border-neutral-700/70 py-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        <span class="font-medium">SKU</span>
                                        <span class="text-right">{{ $product->product->sku }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto pt-4">
                                <button type="button" @click="$wire.addToCart('{{ $product->id }}')"
                                    class="w-full py-3 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-yellow-500 text-white hover:bg-yellow-600 focus:outline-hidden focus:bg-yellow-600 disabled:opacity-50 disabled:pointer-events-none transition">
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
