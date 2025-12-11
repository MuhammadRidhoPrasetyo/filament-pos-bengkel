<?php

namespace App\Filament\Clusters\Reports\Resources\TransactionItems\Pages;

use App\Filament\Clusters\Reports\Resources\TransactionItems\TransactionItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionItem extends CreateRecord
{
    protected static string $resource = TransactionItemResource::class;
}
