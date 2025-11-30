<?php

namespace App\Filament\Clusters\Transactions\Resources\Transactions\Pages;


use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\Transactions\Resources\Transactions\TransactionResource;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
