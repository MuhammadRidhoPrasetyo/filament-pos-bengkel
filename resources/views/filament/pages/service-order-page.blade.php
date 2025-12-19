<x-filament-panels::page>
    <div class="space-y-6">
        {{-- HEADER --}}
        <div
            class="flex flex-col gap-4 rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-50">
                        Service Order Baru
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Input pelanggan, data motor, bagi ke mekanik, lalu generate invoice POS.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <x-filament::button color="gray" icon="heroicon-m-plus" wire:click="addUnit">
                        Tambah Motor
                    </x-filament::button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live="storeId">
                            <option value="">Pilih Bengkel</option>
                            @foreach ($stores as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </x-filament::input.select>
                        {{-- <x-filament::input.error for="storeId" /> --}}
                    </x-filament::input.wrapper>
                </div>

                <div>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live="customerId">
                            <option value="">Pilih Pelanggan</option>
                            @foreach ($customers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </x-filament::input.select>
                        {{-- <x-filament::input.error for="customerId" /> --}}
                    </x-filament::input.wrapper>
                </div>

                {{-- <div class="flex items-end justify-end">
                    <label class="inline-flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-300">
                        <input type="checkbox" wire:model="createInvoiceImmediately"
                            class="size-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500" />
                        <span>Buat invoice POS setelah simpan</span>
                    </label>
                </div> --}}
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- KIRI & TENGAH: UNIT MOTOR --}}
            <div class="space-y-4 lg:col-span-2">
                @foreach ($units as $uIndex => $unit)
                    <div
                        class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                        <div
                            class="flex items-center justify-between gap-2 border-b border-neutral-100 pb-3 dark:border-neutral-700">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex size-8 items-center justify-center rounded-full bg-primary-50 text-sm font-semibold text-primary-700 dark:bg-primary-900/40 dark:text-primary-100">
                                    {{ $uIndex + 1 }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-50">
                                        Unit Motor #{{ $uIndex + 1 }}
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Isi nomor polisi, merk, model, mekanik, lalu tambahkan part / jasa.
                                    </p>
                                </div>
                            </div>

                            <button type="button" wire:click="removeUnit({{ $uIndex }})"
                                class="inline-flex items-center gap-1 rounded-full border border-rose-200 px-3 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50 dark:border-rose-800 dark:text-rose-200 dark:hover:bg-rose-900/40">
                                <x-heroicon-m-trash class="size-3" />
                                Hapus
                            </button>
                        </div>

                        <div class="mt-3 space-y-3">
                            <fieldset class="border border-gray-200 rounded-2xl p-4 sm:p-5 dark:border-neutral-700">
                                <legend
                                    class="px-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">
                                    Info Kendaraan
                                </legend>

                                <div class="grid gap-4 mt-4 md:grid-cols-12">
                                    {{-- Nomor Polisi --}}
                                    <div class="md:col-span-4">
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Nomor Polisi
                                        </label>
                                        <input type="text" wire:model="units.{{ $uIndex }}.plate_number"
                                            placeholder="KT 1234 AB"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" />
                                    </div>

                                    {{-- Merek --}}
                                    <div class="md:col-span-4">
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Merek
                                        </label>
                                        <input type="text" wire:model="units.{{ $uIndex }}.brand"
                                            placeholder="Honda, Yamaha, dll"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" />
                                    </div>

                                    {{-- Model --}}
                                    <div class="md:col-span-2">
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Model
                                        </label>
                                        <input type="text" wire:model="units.{{ $uIndex }}.model"
                                            placeholder="Beat, Nmax, dll"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" />
                                    </div>

                                    {{-- Warna --}}
                                    <div class="md:col-span-2">
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Warna
                                        </label>
                                        <input type="text" wire:model="units.{{ $uIndex }}.color"
                                            placeholder="Hitam, Merah, dll"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" />
                                    </div>
                                </div>
                            </fieldset>

                            {{-- 2. MEKANIK & STATUS --}}
                            <fieldset class="border border-gray-200 rounded-2xl p-4 sm:p-5 dark:border-neutral-700">
                                <legend
                                    class="px-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">
                                    Mekanik & Status Pengerjaan
                                </legend>

                                <div class="grid gap-4 mt-4 md:grid-cols-4">
                                    {{-- Mekanik --}}
                                    <div class="col-span-full">
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Mekanik
                                        </label>
                                        <select multiple wire:model.change="units.{{ $uIndex }}.mechanic_ids"
                                            class="py-2.5 sm:py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                            <option value="">Pilih Mekanik</option>
                                            @foreach ($mechanics as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-[11px] text-gray-500 dark:text-neutral-400">
                                            Tekan Ctrl (Windows) / Cmd (Mac) untuk memilih lebih dari satu mekanik.
                                        </p>
                                    </div>
                                </div>

                                <div class="grid gap-4 mt-4 md:grid-cols-3">
                                    {{-- Status --}}
                                    <div>
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Status
                                        </label>
                                        <select wire:model="units.{{ $uIndex }}.status"
                                            class="py-2.5 sm:py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                            <option value="">Pilih Status</option>
                                            <option value="checkin">Diperiksa</option>
                                            <option value="in_progress">Dalam Proses</option>
                                            <option value="waiting_parts">Menunggu Sparepart</option>
                                            <option value="ready">Siap</option>
                                            <option value="invoiced">Selesai</option>
                                            <option value="cancelled">Batal</option>
                                        </select>
                                    </div>

                                    {{-- Check-in --}}
                                    <div>
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Check-in
                                        </label>
                                        <input type="datetime-local" wire:model="units.{{ $uIndex }}.checkin_at"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" />
                                    </div>

                                    {{-- Selesai --}}
                                    <div>
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Selesai
                                        </label>
                                        <input type="datetime-local"
                                            wire:model="units.{{ $uIndex }}.completed_at"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" />
                                    </div>
                                </div>
                            </fieldset>

                            {{-- 3. CATATAN SERVIS --}}
                            <fieldset class="border border-gray-200 rounded-2xl p-4 sm:p-5 dark:border-neutral-700">
                                <legend
                                    class="px-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">
                                    Catatan Servis
                                </legend>

                                <div class="grid gap-4 mt-4">
                                    {{-- Keluhan --}}
                                    <div>
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Keluhan
                                        </label>
                                        <textarea wire:model="units.{{ $uIndex }}.complaint" placeholder="Masukkan keluhan pelanggan" rows="2"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 min-h-[80px]"></textarea>
                                    </div>

                                    {{-- Diagnosis --}}
                                    <div>
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Diagnosis
                                        </label>
                                        <textarea wire:model="units.{{ $uIndex }}.diagnosis" placeholder="Diagnosa mekanik..." rows="2"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 min-h-[80px]"></textarea>
                                    </div>

                                    {{-- Pekerjaan yang Dilakukan --}}
                                    <div>
                                        <label
                                            class="mb-1 block text-xs font-medium text-gray-700 dark:text-neutral-200">
                                            Pekerjaan Dilakukan
                                        </label>
                                        <textarea wire:model="units.{{ $uIndex }}.work_done" placeholder="Detail pekerjaan..." rows="2"
                                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           disabled:opacity-50 disabled:pointer-events-none
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 min-h-[80px]"></textarea>
                                    </div>
                                </div>
                            </fieldset>

                            {{-- ITEM PART & JASA --}}
                            <div
                                class="mt-6 rounded-2xl border border-gray-200 bg-neutral-50/70 p-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                                <div class="mb-4 flex items-center justify-between gap-2">
                                    <p
                                        class="text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">
                                        Part & Jasa
                                    </p>
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="addItem({{ $uIndex }}, 'part')"
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-500 px-3 py-1 text-xs font-medium text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/70">
                                            + Part
                                        </button>
                                        <button type="button" wire:click="addItem({{ $uIndex }}, 'labor')"
                                            class="inline-flex items-center gap-1 rounded-full bg-sky-500 px-3 py-1 text-xs font-medium text-white shadow-sm hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/70">
                                            + Jasa
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @forelse ($unit['items'] as $iIndex => $item)
                                        <div
                                            class="relative grid items-start gap-3 rounded-2xl bg-white p-4 text-xs shadow-sm ring-1 ring-gray-200 dark:bg-neutral-900 dark:ring-neutral-700 md:grid-cols-12">

                                            {{-- Tombol Hapus – pindah ke pojok kanan atas --}}
                                            <button type="button"
                                                wire:click="removeItem({{ $uIndex }}, {{ $iIndex }})"
                                                class="absolute top-3 right-3 inline-flex items-center justify-center rounded-full bg-rose-50 p-1.5 text-rose-500 hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500/70 dark:bg-rose-900/40 dark:text-rose-200 dark:hover:bg-rose-900/70">
                                                <x-heroicon-m-x-mark class="size-3" />
                                            </button>

                                            {{-- Badge tipe item --}}
                                            <div class="md:col-span-2 flex md:flex-col items-start gap-2">
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-neutral-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                                                    {{ $item['item_type'] === 'part' ? 'Part' : 'Jasa' }}
                                                </span>

                                                <p
                                                    class="hidden md:block text-[10px] text-neutral-400 dark:text-neutral-500">
                                                    Baris #{{ $iIndex + 1 }}
                                                </p>
                                            </div>

                                            {{-- Produk (opsional) --}}
                                            <div class="md:col-span-12 my-3">
                                                <label
                                                    class="mb-1 block text-[11px] font-medium text-neutral-700 dark:text-neutral-200">
                                                    Produk
                                                </label>

                                                <select
                                                    wire:model.change="units.{{ $uIndex }}.items.{{ $iIndex }}.product_id"
                                                    class="py-2.5 sm:py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500">
                                                    <option value="">Pilih Produk (opsional)</option>
                                                    @foreach ($products as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <p class="mt-0.5 text-[10px] text-neutral-400 dark:text-neutral-500">
                                                    Jika produk dipilih, stok & harga dapat terisi otomatis.
                                                </p>
                                            </div>

                                            {{-- Deskripsi --}}
                                            <div class="md:col-span-4">
                                                <label
                                                    class="mb-1 block text-[11px] font-medium text-neutral-700 dark:text-neutral-200">
                                                    Deskripsi
                                                </label>
                                                <input type="text" placeholder="Deskripsi part / jasa"
                                                    wire:model="units.{{ $uIndex }}.items.{{ $iIndex }}.description"
                                                    class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500" />
                                                <p class="mt-0.5 text-[10px] text-neutral-400">
                                                    Deskripsi singkat pekerjaan / part.
                                                </p>
                                            </div>

                                            {{-- Qty --}}
                                            <div class="md:col-span-2">
                                                <label
                                                    class="mb-1 block text-[11px] font-medium text-neutral-700 dark:text-neutral-200">
                                                    Qty
                                                </label>
                                                <input type="number"
                                                    @if (!empty($item['max_qty'])) max="{{ $item['max_qty'] }}" @endif
                                                    placeholder="Qty"
                                                    wire:model.change="units.{{ $uIndex }}.items.{{ $iIndex }}.qty"
                                                    class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500" />

                                                @if (!empty($item['max_qty']))
                                                    <p class="mt-0.5 text-[10px] text-neutral-400">
                                                        Stok tersedia: {{ $item['max_qty'] }}
                                                    </p>
                                                @else
                                                    <p class="mt-0.5 text-[10px] text-neutral-400">
                                                        Tidak dibatasi stok (jasa / non-stock).
                                                    </p>
                                                @endif
                                            </div>

                                            {{-- Harga Satuan --}}
                                            <div class="md:col-span-3">
                                                <label
                                                    class="mb-1 block text-[11px] font-medium text-neutral-700 dark:text-neutral-200">
                                                    Harga Satuan
                                                </label>
                                                <input type="number" placeholder="Harga"
                                                    wire:model.live.debounce.250ms="units.{{ $uIndex }}.items.{{ $iIndex }}.unit_price"
                                                    @disabled(($item['pricing_mode'] ?? 'fixed') === 'fixed')
                                                    class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg text-sm
                           focus:border-blue-500 focus:ring-blue-500
                           dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500" />

                                                @if (($item['pricing_mode'] ?? 'fixed') === 'fixed')
                                                    <p class="mt-0.5 text-[10px] text-neutral-400">
                                                        Harga di-lock (kategori: fixed).
                                                    </p>
                                                @else
                                                    <p class="mt-0.5 text-[10px] text-amber-500">
                                                        Harga bisa diubah (kategori: editable).
                                                    </p>
                                                @endif
                                            </div>

                                            {{-- Total --}}
                                            <div class="md:col-span-3 flex items-end justify-end">
                                                <p class="text-sm font-bold text-neutral-800 dark:text-neutral-100">
                                                    Rp {{ number_format($item['line_total'] ?? 0, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            Belum ada item. Tambahkan part atau jasa.
                                        </p>
                                    @endforelse
                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach

                @if (empty($units))
                    <div
                        class="rounded-2xl border border-dashed border-neutral-300 bg-neutral-50 p-6 text-center text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-900/40 dark:text-neutral-300">
                        Belum ada motor. Klik <span class="font-semibold">“Tambah Motor”</span> untuk mulai membuat
                        service order.
                    </div>
                @endif
            </div>

            {{-- KANAN: RINGKASAN --}}
            <div class="space-y-4">
                @php
                    $summarySubtotal = 0;
                    foreach ($units as $unit) {
                        foreach ($unit['items'] ?? [] as $item) {
                            $summarySubtotal += (float) ($item['line_total'] ?? 0);
                        }
                    }
                @endphp

                <div
                    class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-50">
                        Ringkasan Estimasi
                    </h3>
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        Total estimasi biaya dari seluruh motor di service order ini.
                    </p>

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-neutral-500">Jumlah Motor</span>
                            <span class="font-semibold text-neutral-900 dark:text-neutral-50">
                                {{ count($units) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-neutral-500">Perkiraan Total</span>
                            <span class="text-base font-semibold text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($summarySubtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <x-filament::button wire:click.prevent="save(false)" color="primary"
                            icon="heroicon-m-document" class="w-full justify-center">
                            Simpan Service Order
                        </x-filament::button>

                        {{-- <x-filament::button wire:click.prevent="save(true)" color="primary"
                            icon="heroicon-m-banknotes" class="w-full justify-center">
                            Simpan & Buat Invoice POS
                        </x-filament::button> --}}

                        <p class="mt-2 text-[11px] text-neutral-400 dark:text-neutral-500">
                            Invoice POS yang dibuat akan berisi gabungan part & jasa dari semua motor
                            (type: <span class="font-semibold text-neutral-500 dark:text-neutral-300">service</span>).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
