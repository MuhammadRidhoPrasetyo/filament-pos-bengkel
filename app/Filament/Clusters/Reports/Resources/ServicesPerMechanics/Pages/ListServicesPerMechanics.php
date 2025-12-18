<?php

namespace App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Pages;

use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\ServicesPerMechanicsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServicesPerMechanics extends ListRecords
{
    protected static string $resource = ServicesPerMechanicsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
