<?php

namespace App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Pages;

use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\ServicesPerMechanicsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServicesPerMechanics extends EditRecord
{
    protected static string $resource = ServicesPerMechanicsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
