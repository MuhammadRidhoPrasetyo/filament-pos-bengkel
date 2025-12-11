<?php

namespace App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Pages;

use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\SalesPerCashierResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSalesPerCashier extends EditRecord
{
    protected static string $resource = SalesPerCashierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
