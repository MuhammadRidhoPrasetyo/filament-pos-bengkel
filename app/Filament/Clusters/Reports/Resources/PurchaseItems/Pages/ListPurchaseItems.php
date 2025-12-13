<?php

namespace App\Filament\Clusters\Reports\Resources\PurchaseItems\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Clusters\Reports\Resources\PurchaseItems\PurchaseItemResource;
use App\Filament\Clusters\Reports\Resources\PurchaseItems\Widgets\PurchaseItemsStats;

class ListPurchaseItems extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = PurchaseItemResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PurchaseItemsStats::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
