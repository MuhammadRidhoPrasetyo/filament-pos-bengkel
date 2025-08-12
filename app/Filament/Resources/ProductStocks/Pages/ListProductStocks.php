<?php

namespace App\Filament\Resources\ProductStocks\Pages;

use App\Models\ProductStock;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\ProductStocks\ProductStockResource;

class ListProductStocks extends ListRecords
{
    protected static string $resource = ProductStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
