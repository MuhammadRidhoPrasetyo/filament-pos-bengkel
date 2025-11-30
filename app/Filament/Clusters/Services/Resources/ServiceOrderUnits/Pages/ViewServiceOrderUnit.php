<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Pages;

use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\ServiceOrderUnitResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceOrderUnit extends ViewRecord
{
    protected static string $resource = ServiceOrderUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
