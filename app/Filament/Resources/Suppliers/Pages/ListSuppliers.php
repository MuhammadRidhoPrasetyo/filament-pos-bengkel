<?php

namespace App\Filament\Resources\Suppliers\Pages;

use App\Filament\Resources\Suppliers\SupplierResource;
use App\Models\Supplier;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Tabs\Tab;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Supplier' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'supplier')),
            'Customer' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'customer')),
            'Keduanya' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'both')),
        ];
    }
}
