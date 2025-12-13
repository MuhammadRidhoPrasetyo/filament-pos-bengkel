<?php

namespace App\Filament\Clusters\Reports\Resources\TransactionItems\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Clusters\Reports\Resources\TransactionItems\TransactionItemResource;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Widgets\TransactionItemsStats;


class ListTransactionItems extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = TransactionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TransactionItemsStats::class,
        ];
    }
}
