<?php

namespace App\Filament\Clusters\Transactions\Resources\TransactionItems\Pages;

use App\Filament\Clusters\Transactions\Resources\TransactionItems\TransactionItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransactionItem extends EditRecord
{
    protected static string $resource = TransactionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
