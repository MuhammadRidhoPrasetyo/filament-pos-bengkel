<?php

namespace App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Pages;

use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\ServicesPerMechanicsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServicesPerMechanics extends ViewRecord
{
    protected static string $resource = ServicesPerMechanicsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
