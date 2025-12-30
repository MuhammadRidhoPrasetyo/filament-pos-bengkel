<?php

namespace App\Filament\Resources\StockTransfers\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StockTransfers\StockTransferResource;

class ListStockTransfers extends ListRecords
{
    protected static string $resource = StockTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
