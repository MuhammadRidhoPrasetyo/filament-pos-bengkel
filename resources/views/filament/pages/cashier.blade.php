<x-filament-panels::page>
    <div class="grid grid-cols-12">
        <div class="col-span-12 md:col-span-8 lg:col-span-8 flex flex-col  gap-8">
            <div class="w-full">
                <x-filament::input.wrapper class="w-full">
                    <x-filament::input type="text" wire:model="name" placeholder="Ketik nama barang..." />
                </x-filament::input.wrapper>
            </div>
            <div class="flex w-full gap-8">
                <div class="w-1/2">
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.change="productCategoryId" searchable>
                            <option value="">--- Pilih Kategori ---</option>
                            @foreach ($this->productCategories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>
                <div class="w-1/2">
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

            {{-- <x-filament::input.wrapper class="w-full">
                <x-filament::input type="text" wire:model="name" placeholder="Ketik nama barang..." />
            </x-filament::input.wrapper> --}}
        </div>
    </div>

    <div class="grid grid-cols-12 text-neutral-100 gap-8">
        <div class="col-span-12 md:col-span-8 lg:col-span-8 flex flex-col md:flex-col lg:flex-row gap-8">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-8">

                <!-- Card 1 -->
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
                            <h3 class="text-base md:text-lg font-semibold">Beija Flor</h3>
                            <p class="mt-2 text-sm md:text-base font-semibold">$5.50</p>
                        </div>

                        <a href="#" class="absolute inset-0" aria-label="View Beija Flor"></a>
                    </div>

                    <div class="mt-6 text-sm">
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Tasting Notes:</span>
                                <span class="text-right">Hazelnut, Grape, Milk Chocolate</span>
                            </div>
                        </div>
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Origin:</span>
                                <span class="text-right">Brazil</span>
                            </div>
                        </div>
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Region:</span>
                                <span class="text-right">São Paulo</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <a href="#"
                            class="w-full inline-flex justify-center items-center rounded-2xl px-4 py-3 text-sm font-medium whitespace-nowrap
                      bg-yellow-400 text-black hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition">
                            Buy now
                        </a>
                    </div>
                </div>
                <!-- End Card 1 -->

                <!-- Card 2 -->
                <div class="group flex flex-col">
                    <div class="relative">
                        <div class="rounded-3xl bg-neutral-800 p-4">
                            <div class="aspect-square overflow-hidden rounded-2xl">
                                <img class="h-full w-full object-cover"
                                    src="https://images.unsplash.com/photo-1445077100181-a33e9ac94db0?q=80&w=1200&auto=format&fit=crop"
                                    alt="El Mirador" loading="lazy">
                            </div>
                        </div>

                        <div class="pt-6">
                            <h3 class="text-base md:text-lg font-semibold">El Mirador</h3>
                            <p class="mt-2 text-sm md:text-base font-semibold">$7.50</p>
                        </div>

                        <a href="#" class="absolute inset-0" aria-label="View El Mirador"></a>
                    </div>

                    <div class="mt-6 text-sm">
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Tasting Notes:</span>
                                <span class="text-right">Red Apple, Caramel, Almond</span>
                            </div>
                        </div>
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Origin:</span>
                                <span class="text-right">Colombia</span>
                            </div>
                        </div>
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Region:</span>
                                <span class="text-right">Manizales</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <a href="#"
                            class="w-full inline-flex justify-center items-center rounded-2xl px-4 py-3 text-sm font-medium whitespace-nowrap
                      bg-yellow-400 text-black hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition">
                            Buy now
                        </a>
                    </div>
                </div>
                <!-- End Card 2 -->

                <!-- Card 3 -->
                <div class="group flex flex-col">
                    <div class="relative">
                        <div class="rounded-3xl bg-neutral-800 p-4">
                            <div class="aspect-square overflow-hidden rounded-2xl">
                                <img class="h-full w-full object-cover"
                                    src="https://images.unsplash.com/photo-1459257868276-5e65389e2722?q=80&w=1200&auto=format&fit=crop"
                                    alt="Pedra Branca" loading="lazy">
                            </div>
                        </div>

                        <div class="pt-6">
                            <h3 class="text-base md:text-lg font-semibold">Pedra Branca</h3>
                            <p class="mt-2 text-sm md:text-base font-semibold">$2.10</p>
                        </div>

                        <a href="#" class="absolute inset-0" aria-label="View Pedra Branca"></a>
                    </div>

                    <div class="mt-6 text-sm">
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Tasting Notes:</span>
                                <span class="text-right">Red Apple, Walnut, Milk Chocolate</span>
                            </div>
                        </div>
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Origin:</span>
                                <span class="text-right">Brazil</span>
                            </div>
                        </div>
                        <div class="border-t border-neutral-700/70 py-3">
                            <div class="grid grid-cols-2 gap-2">
                                <span class="font-medium">Region:</span>
                                <span class="text-right">São Paulo</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <a href="#"
                            class="w-full inline-flex justify-center items-center rounded-2xl px-4 py-3 text-sm font-medium whitespace-nowrap
                      bg-yellow-400 text-black hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition">
                            Buy now
                        </a>
                    </div>
                </div>
                <!-- End Card 3 -->

            </div>
        </div>
        <div class="col-span-12 md:col-span-4 lg:col-span-4 flex flex-col md:flex-col lg:flex-row gap-8">
            <div class="grid grid-cols-1 min-w-full gap-8 border border-neutral-700/70 rounded-3xl p-4">

            </div>
        </div>
    </div>
</x-filament-panels::page>
