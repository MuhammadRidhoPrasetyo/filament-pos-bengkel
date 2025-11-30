<?php

namespace App\Filament\Clusters\Purchases\Resources\PurchaseItems\Pages;

use App\Filament\Clusters\Purchases\Resources\PurchaseItems\PurchaseItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseItems extends ListRecords
{
    protected static string $resource = PurchaseItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
