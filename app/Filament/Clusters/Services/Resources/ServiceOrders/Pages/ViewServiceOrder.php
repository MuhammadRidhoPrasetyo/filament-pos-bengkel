<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Pages;

use App\Filament\Clusters\Services\Resources\ServiceOrders\ServiceOrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceOrder extends ViewRecord
{
    protected static string $resource = ServiceOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
