<?php

namespace App\Filament\Clusters\Transactions\Resources\TransactionItems\Pages;

use App\Filament\Clusters\Transactions\Resources\TransactionItems\TransactionItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionItem extends CreateRecord
{
    protected static string $resource = TransactionItemResource::class;
}
