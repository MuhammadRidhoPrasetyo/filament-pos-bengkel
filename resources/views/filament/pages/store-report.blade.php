<x-filament::page>
    <div class="space-y-10">

        {{-- ========================================= --}}
        {{-- FILTER SECTION - PURE PRELINE UI --}}
        {{-- ========================================= --}}
        <x-filament::section icon="heroicon-m-funnel">
            <x-slot name="heading">
                Filter Laporan
            </x-slot>

            <div class="flex flex-col gap-4 sm:flex-row">
                <x-filament::input.wrapper class="flex-1">
                    <x-filament::input.select wire:model="storeId">
                        <option value="">Pilih Bengkel</option>
                        @foreach (\App\Models\Store::all() as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper class="flex-1">
                    <x-filament::input.select wire:model="cashierId">
                        <option value="">Pilih Kasir</option>
                        @foreach (\App\Models\User::all() as $cashier)
                            <option value="{{ $cashier->id }}">{{ $cashier->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper class="flex-1">
                    <x-filament::input.select wire:model="categoryId">
                        <option value="">Pilih Kategori</option>
                        @foreach (\App\Models\ProductCategory::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row mt-6">
                <div class="flex-1">
                    <x-filament::input.wrapper>
                        <x-filament::input type="date" wire:model="dateFrom" />
                    </x-filament::input.wrapper>
                </div>

                {{-- Date To --}}
                <div class="flex-1">
                    <x-filament::input.wrapper>
                        <x-filament::input type="date" wire:model="dateTo" />
                    </x-filament::input.wrapper>
                </div>
            </div>

            {{-- Filter Button --}}
            <div class="mt-4">
                <x-filament::button wire:click="filter" color="primary" icon="heroicon-m-funnel">
                    Terapkan Filter
                </x-filament::button>

                <x-filament::button wire:click="exportPdf" color="danger" icon="heroicon-m-document">
                    PDF
                </x-filament::button>

                <x-filament::button wire:click="exportExcel" color="success" icon="heroicon-m-table-cells">
                    Excel
                </x-filament::button>
            </div>

        </x-filament::section>

        {{-- ========================================= --}}
        {{-- SUMMARY CARDS --}}
        {{-- ========================================= --}}

        {{-- SECTION STATS OMZET / PENGELUARAN / PROFIT --}}
        <div class="w-full py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                {{-- Card Omzet --}}
                <div
                    class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                    <div class="p-4 md:p-5 flex gap-x-4">
                        <div
                            class="shrink-0 flex justify-center items-center size-11 bg-green-100 rounded-lg dark:bg-green-900/40">
                            {{-- Icon uang --}}
                            <svg class="shrink-0 size-5 text-green-700 dark:text-green-300"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 8h.01M20 16h.01" />
                            </svg>
                        </div>

                        <div class="grow">
                            <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                                Omzet
                            </p>
                            <div class="mt-1 flex items-center gap-x-2">
                                <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-neutral-200">
                                    Rp {{ number_format($summary['omzet'] ?? 0, 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Pengeluaran --}}
                <div
                    class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                    <div class="p-4 md:p-5 flex gap-x-4">
                        <div
                            class="shrink-0 flex justify-center items-center size-11 bg-red-100 rounded-lg dark:bg-red-900/40">
                            {{-- Icon keluar uang --}}
                            <svg class="shrink-0 size-5 text-red-700 dark:text-red-300"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3v18" />
                                <path d="M8 7h4a4 4 0 0 1 0 8H8" />
                                <path d="M8 3h8" />
                                <path d="M8 21h8" />
                            </svg>
                        </div>

                        <div class="grow">
                            <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                                Pengeluaran
                            </p>
                            <div class="mt-1 flex items-center gap-x-2">
                                <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-neutral-200">
                                    Rp {{ number_format($summary['pengeluaran'] ?? 0, 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Profit --}}
                <div
                    class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-800">
                    <div class="p-4 md:p-5 flex gap-x-4">
                        <div
                            class="shrink-0 flex justify-center items-center size-11 bg-blue-100 rounded-lg dark:bg-blue-900/40">
                            {{-- Icon profit --}}
                            <svg class="shrink-0 size-5 text-blue-700 dark:text-blue-300"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 17 9 11 13 15 21 7" />
                                <polyline points="14 7 21 7 21 14" />
                            </svg>
                        </div>

                        <div class="grow">
                            <p class="text-xs uppercase text-gray-500 dark:text-neutral-500">
                                Profit
                            </p>
                            <div class="mt-1 flex items-center gap-x-2">
                                @php $profit = $summary['profit'] ?? 0; @endphp
                                <h3
                                    class="text-xl sm:text-2xl font-semibold
                            {{ $profit >= 0 ? 'text-blue-800 dark:text-blue-200' : 'text-red-700 dark:text-red-300' }}">
                                    Rp {{ number_format($profit, 0, ',', '.') }}
                                </h3>

                                <span
                                    class="inline-flex items-center gap-x-1 py-0.5 px-2 rounded-full text-xs font-medium
                                {{ $profit >= 0 ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-900 dark:bg-red-800 dark:text-red-100' }}">
                                    {{ $profit >= 0 ? 'Laba' : 'Rugi' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        {{-- ========================================= --}}
        {{-- HEATMAP CHART --}}
        {{-- ========================================= --}}
        {{-- <div
            class="p-6 rounded-2xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-100 mb-4">
                Grafik Omzet Per Jam
            </h3>
            <div id="chart-hourly" style="height: 320px;"></div>
        </div> --}}


        {{-- ========================================= --}}
        {{-- BARANG MASUK --}}
        {{-- ========================================= --}}
        <div
            class="p-6 rounded-2xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 shadow-sm">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-100">
                        Barang Masuk
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-neutral-400">
                        Data barang masuk berdasarkan filter tanggal & bengkel.
                    </p>
                </div>

                {{-- Export Buttons --}}
                {{-- <div class="flex gap-2">
                    <button
                        class="inline-flex items-center gap-x-1 px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg bg-white text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-700"
                        wire:click="exportBarangMasukPdf">
                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M12 12v9" />
                            <path d="M6 21h12" />
                            <path d="M12 3v4" />
                            <path d="m16 7-4 4-4-4" />
                        </svg>
                        PDF
                    </button>

                    <button
                        class="inline-flex items-center gap-x-1 px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg bg-white text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-700"
                        wire:click="exportBarangMasukExcel">
                        <svg class="size-3.5" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M5.884 6.68a.5.5 0 0 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 1 0 .768.64L8 10.781l2.116 2.54a.5.5 0 1 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z" />
                            <path
                                d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                        </svg>
                        Excel
                    </button>
                </div> --}}
            </div>

            {{-- Table --}}
            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800/60">
                    <tr>
                        <th
                            class="px-4 py-2 text-left text-[11px] font-semibold uppercase text-gray-700 dark:text-neutral-300">
                            Produk
                        </th>

                        <th
                            class="px-4 py-2 text-center text-[11px] font-semibold uppercase text-gray-700 dark:text-neutral-300">
                            Qty
                        </th>

                        <th
                            class="px-4 py-2 text-left text-[11px] font-semibold uppercase text-gray-700 dark:text-neutral-300">
                            Tanggal
                        </th>

                        <th
                            class="px-4 py-2 text-left text-[11px] font-semibold uppercase text-gray-700 dark:text-neutral-300">
                            Catatan
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse ($barangMasuk as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/40 transition">
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-neutral-100">
                                {{ $item->name }}
                            </td>

                            <td
                                class="px-4 py-2 text-sm text-center font-semibold text-gray-900 dark:text-neutral-100">
                                {{ $item->quantity }}
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-neutral-300">
                                {{ date('d M Y H:i', strtotime($item->occurred_at)) }}
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-neutral-400">
                                {{ $item->note ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="px-4 py-3 text-center text-sm text-gray-500 dark:text-neutral-400">
                                Tidak ada data barang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @php
                $totalQtyMasuk = collect($barangMasuk)->sum('quantity');
            @endphp

            {{-- Footer --}}
            <div class="mt-4 border-t border-gray-200 dark:border-neutral-800 pt-3">
                <p class="text-sm text-gray-700 dark:text-neutral-200">
                    Total Barang Masuk:
                    <span class="font-semibold">
                        {{ number_format($totalQtyMasuk, 0, ',', '.') }}
                    </span>
                </p>
            </div>
        </div>


        {{-- ========================================= --}}
        {{-- BARANG KELUAR --}}
        {{-- ========================================= --}}
        @php
            // Hitung total qty barang keluar (aman untuk array maupun Collection)
            $totalQtyKeluar = is_array($barangKeluar)
                ? collect($barangKeluar)->sum('quantity') // sesuaikan ke field yg dipakai
                : $barangKeluar->sum('quantity');
        @endphp

        <div
            class="p-6 rounded-2xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 shadow-sm">
            <div class="flex items-center justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-100">
                        Barang Keluar
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-neutral-400">
                        Data barang keluar berdasarkan filter tanggal &amp; bengkel.
                    </p>
                </div>

                {{-- <div class="text-right">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-neutral-400">
                        Total Qty Keluar
                    </p>
                    <p class="text-base font-semibold text-rose-600 dark:text-rose-400">
                        {{ number_format($totalQtyKeluar ?? 0, 0, ',', '.') }}
                    </p>
                </div> --}}
            </div>

            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-neutral-300">
                            Produk
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 dark:text-neutral-300">
                            Qty
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-neutral-300">
                            Tanggal
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-neutral-300">
                            Catatan
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse ($barangKeluar as $item)
                        <tr class="bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800/40">
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-neutral-100">
                                {{ $item->name }}
                            </td>

                            <td class="px-4 py-2 text-sm text-center font-medium text-gray-700 dark:text-neutral-200">
                                {{ $item->quantity }}
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-neutral-300">
                                {{ date('d M Y H:i', strtotime($item->occurred_at)) }}
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-neutral-400">
                                {{ $item->note ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="px-4 py-3 text-center text-sm text-gray-500 dark:text-neutral-400">
                                Tidak ada data barang keluar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Footer --}}
            <div class="mt-4 border-t border-gray-200 dark:border-neutral-800 pt-3">
                <p class="text-sm text-gray-700 dark:text-neutral-200">
                    Total Barang Keluar:
                    <span class="font-semibold">
                        {{ number_format($totalQtyKeluar, 0, ',', '.') }}
                    </span>
                </p>
            </div>
        </div>

    </div>


    {{-- ========================================= --}}
    {{-- APEXCHARTS --}}
    {{-- ========================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // document.addEventListener('livewire:load', () => {
            let chart = new ApexCharts(document.querySelector("#chart-hourly"), {
                chart: {
                    type: 'heatmap',
                    toolbar: {
                        show: false
                    }
                },
                colors: ["#3b82f6"],
                series: [{
                    name: "Omzet",
                    data: @json(array_values($hourlySales)),
                }],
                xaxis: {
                    categories: [...Array(24).keys()].map(h => h.toString().padStart(2, '0') + ":00")
                },
                dataLabels: {
                    enabled: false
                },
            });

            chart.render();

            // Livewire.on('filterUpdated', () => location.reload());
        // });
    </script>

</x-filament::page>
