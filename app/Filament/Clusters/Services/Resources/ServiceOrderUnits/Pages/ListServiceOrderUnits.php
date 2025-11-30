<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Pages;

use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\ServiceOrderUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceOrderUnits extends ListRecords
{
    protected static string $resource = ServiceOrderUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
