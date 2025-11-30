<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Pages;

use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\ServiceOrderUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceOrderUnit extends EditRecord
{
    protected static string $resource = ServiceOrderUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
