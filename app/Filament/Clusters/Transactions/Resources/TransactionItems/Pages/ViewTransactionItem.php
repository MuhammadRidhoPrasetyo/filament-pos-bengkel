<?php

namespace App\Filament\Clusters\Transactions\Resources\TransactionItems\Pages;

use App\Filament\Clusters\Transactions\Resources\TransactionItems\TransactionItemResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransactionItem extends ViewRecord
{
    protected static string $resource = TransactionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
