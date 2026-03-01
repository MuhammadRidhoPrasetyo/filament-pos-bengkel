<?php

namespace App\Filament\Resources\CashFlowCategories\Pages;

use App\Filament\Resources\CashFlowCategories\CashFlowCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCashFlowCategories extends ManageRecords
{
    protected static string $resource = CashFlowCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
