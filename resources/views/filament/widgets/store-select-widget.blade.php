<x-filament-widgets::widget>
    <x-filament::section>
        <x-filament::input.wrapper>
            <x-filament::input.select wire:model.change="storeId">
                @foreach ($this->stores as $key => $value)
                    <option value="{{ $key }}" @selected($this->storeId == $key)>{{ $value }}</option>
                @endforeach
            </x-filament::input.select>
        </x-filament::input.wrapper>
    </x-filament::section>
</x-filament-widgets::widget>
