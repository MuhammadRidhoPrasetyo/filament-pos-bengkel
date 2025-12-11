<?php

namespace App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Pages;

use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\SalesPerCashierResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesPerCashier extends ViewRecord
{
    protected static string $resource = SalesPerCashierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
