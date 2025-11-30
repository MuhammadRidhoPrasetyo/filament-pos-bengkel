<x-filament-panels::page>
    <div class="space-y-6">
        {{-- HEADER --}}
        <div
            class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-50">
                        Service Order Baru
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
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
                            <option value="">Pilih Toko</option>
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

                <div class="flex items-end justify-end">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                        <input type="checkbox" wire:model="createInvoiceImmediately"
                            class="size-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500" />
                        <span>Buat invoice POS setelah simpan</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- KIRI & TENGAH: UNIT MOTOR --}}
            <div class="space-y-4 lg:col-span-2">
                @foreach ($units as $uIndex => $unit)
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <div
                            class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3 dark:border-slate-700">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex size-8 items-center justify-center rounded-full bg-primary-50 text-sm font-semibold text-primary-700 dark:bg-primary-900/40 dark:text-primary-100">
                                    {{ $uIndex + 1 }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                                        Unit Motor #{{ $uIndex + 1 }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
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
                            <div class="grid gap-4 md:grid-cols-4">
                                {{-- Mekanik --}}
                                <div class="col-span-full">
                                    <x-filament::input.wrapper label="Mekanik">
                                        <x-filament::input.select multiple
                                            wire:model.change="units.{{ $uIndex }}.mechanic_ids">
                                            <option value="" selected>Pilih Mekanik</option>
                                            @foreach ($mechanics as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </x-filament::input.select>
                                    </x-filament::input.wrapper>
                                </div>

                            </div>

                            <div class="grid gap-4 mt-6 md:grid-cols-12">
                                {{-- Nomor Polisi (lebih lebar) --}}
                                <div class="md:col-span-4">
                                    <x-filament::input.wrapper label="Nomor Polisi">
                                        <x-filament::input wire:model="units.{{ $uIndex }}.plate_number"
                                            placeholder="KT 1234 AB" />
                                    </x-filament::input.wrapper>
                                </div>

                                {{-- Merek --}}
                                <div class="md:col-span-4">
                                    <x-filament::input.wrapper label="Merek">
                                        <x-filament::input wire:model="units.{{ $uIndex }}.brand"
                                            placeholder="Honda, Yamaha, dll" />
                                    </x-filament::input.wrapper>
                                </div>

                                {{-- Model --}}
                                <div class="md:col-span-2">
                                    <x-filament::input.wrapper label="Model">
                                        <x-filament::input wire:model="units.{{ $uIndex }}.model"
                                            placeholder="Beat, Nmax, dll" />
                                    </x-filament::input.wrapper>
                                </div>

                                {{-- Warna --}}
                                <div class="md:col-span-2">
                                    <x-filament::input.wrapper label="Warna">
                                        <x-filament::input wire:model="units.{{ $uIndex }}.color"
                                            placeholder="Hitam, Merah, dll" />
                                    </x-filament::input.wrapper>
                                </div>
                            </div>

                            <div class="grid gap-4 mt-6">

                                {{-- Keluhan --}}
                                <x-filament::input.wrapper label="Keluhan">
                                    <x-filament::input type="text" wire:model="units.{{ $uIndex }}.complaint"
                                        placeholder="Masukkan keluhan pelanggan" />
                                </x-filament::input.wrapper>

                                {{-- Diagnosis --}}
                                <x-filament::input.wrapper label="Diagnosis">
                                    <x-filament::input wire:model="units.{{ $uIndex }}.diagnosis" rows="2"
                                        placeholder="Diagnosa mekanik..." />
                                </x-filament::input.wrapper>

                                {{-- Pekerjaan yang dilakukan --}}
                                <x-filament::input.wrapper label="Pekerjaan Dilakukan">
                                    <x-filament::input wire:model="units.{{ $uIndex }}.work_done" rows="2"
                                        placeholder="Detail pekerjaan..." />
                                </x-filament::input.wrapper>

                            </div>

                            <div class="grid gap-4 md:grid-cols-3 mt-6">

                                {{-- Status --}}
                                <div>
                                    <x-filament::input.wrapper label="Status">
                                        <x-filament::input.select wire:model="units.{{ $uIndex }}.status">
                                            <option value="">Pilih Status</option>
                                            <option value="checkin">Diperiksa</option>
                                            <option value="in_progress">Dalam Proses</option>
                                            <option value="waiting_parts">Menunggu Sparepart</option>
                                            <option value="ready">Siap</option>
                                            <option value="invoiced">Selesai</option>
                                            <option value="cancelled">Batal</option>
                                        </x-filament::input.select>
                                    </x-filament::input.wrapper>
                                </div>

                                {{-- Check in --}}
                                <div>
                                    <x-filament::input.wrapper label="Check-in">
                                        <x-filament::input type="datetime-local"
                                            wire:model="units.{{ $uIndex }}.checkin_at" />
                                    </x-filament::input.wrapper>
                                </div>

                                {{-- Selesai --}}
                                <div>
                                    <x-filament::input.wrapper label="Selesai">
                                        <x-filament::input type="datetime-local"
                                            wire:model="units.{{ $uIndex }}.completed_at" />
                                    </x-filament::input.wrapper>
                                </div>

                            </div>

                            {{-- ITEM PART & JASA --}}
                            <div
                                class="mt-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-3 dark:border-slate-700 dark:bg-slate-800/50">
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <p
                                        class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        Part & Jasa
                                    </p>
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="addItem({{ $uIndex }}, 'part')"
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-500 px-3 py-1 text-xs font-medium text-white hover:bg-emerald-600">
                                            + Part
                                        </button>
                                        <button type="button" wire:click="addItem({{ $uIndex }}, 'labor')"
                                            class="inline-flex items-center gap-1 rounded-full bg-sky-500 px-3 py-1 text-xs font-medium text-white hover:bg-sky-600">
                                            + Jasa
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @forelse ($unit['items'] as $iIndex => $item)
                                        <div
                                            class="grid items-center gap-2 rounded-xl bg-white p-2 text-xs shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700 md:grid-cols-12">
                                            <div class="md:col-span-1">
                                                <span
                                                    class="mb-1 inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                                    {{ $item['item_type'] === 'part' ? 'Part' : 'Jasa' }}
                                                </span>
                                            </div>
                                            <div class="md:col-span-3">

                                                <x-filament::input.select
                                                    wire:model="units.{{ $uIndex }}.items.{{ $iIndex }}.product_id">
                                                    <option value="">Pilih Produk (opsional)</option>
                                                    @foreach ($products as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}
                                                        </option>
                                                    @endforeach
                                                </x-filament::input.select>
                                            </div>

                                            <div class="md:col-span-3">
                                                <x-filament::input type="text" placeholder="Deskripsi part / jasa"
                                                    wire:model="units.{{ $uIndex }}.items.{{ $iIndex }}.description" />
                                            </div>

                                            <div class="md:col-span-2">
                                                <x-filament::input type="number" min="1" placeholder="Qty"
                                                    wire:model.live="units.{{ $uIndex }}.items.{{ $iIndex }}.qty" />
                                            </div>

                                            <div class="md:col-span-2">
                                                <x-filament::input type="number" min="0" step="0.01"
                                                    placeholder="Harga"
                                                    wire:model.live.debounce.250ms="units.{{ $uIndex }}.items.{{ $iIndex }}.unit_price"
                                                    @if (($item['pricing_mode'] ?? 'fixed') === 'fixed') disabled @endif />

                                                @if (($item['pricing_mode'] ?? 'fixed') === 'fixed')
                                                    <p class="mt-0.5 text-[10px] text-slate-400">
                                                        Harga di-lock (kategori: fixed).
                                                    </p>
                                                @else
                                                    <p class="mt-0.5 text-[10px] text-amber-500">
                                                        Harga bisa diubah (kategori: editable).
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="flex items-center justify-between gap-2 md:col-span-1">
                                                <p
                                                    class="text-[11px] font-semibold text-slate-700 dark:text-slate-100">
                                                    Rp {{ number_format($item['line_total'] ?? 0, 0, ',', '.') }}
                                                </p>
                                                <button type="button"
                                                    wire:click="removeItem({{ $uIndex }}, {{ $iIndex }})"
                                                    class="inline-flex items-center justify-center rounded-full bg-rose-50 p-1 text-rose-500 hover:bg-rose-100 dark:bg-rose-900/40 dark:text-rose-200 dark:hover:bg-rose-900/70">
                                                    <x-heroicon-m-x-mark class="size-3" />
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-500">
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
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-300">
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
                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                        Ringkasan Estimasi
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Total estimasi biaya dari seluruh motor di service order ini.
                    </p>

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Jumlah Motor</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-50">
                                {{ count($units) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Perkiraan Total</span>
                            <span class="text-base font-semibold text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($summarySubtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <x-filament::button wire:click.prevent="save(false)" color="gray"
                            icon="heroicon-m-document" class="w-full justify-center">
                            Simpan Service Order Saja
                        </x-filament::button>

                        <x-filament::button wire:click.prevent="save(true)" color="primary"
                            icon="heroicon-m-banknotes" class="w-full justify-center">
                            Simpan & Buat Invoice POS
                        </x-filament::button>

                        <p class="mt-2 text-[11px] text-slate-400 dark:text-slate-500">
                            Invoice POS yang dibuat akan berisi gabungan part & jasa dari semua motor
                            (type: <span class="font-semibold text-slate-500 dark:text-slate-300">service</span>).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
