<?php

namespace App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Pages;

use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\SalesPerCashierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalesPerCashiers extends ListRecords
{
    protected static string $resource = SalesPerCashierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
