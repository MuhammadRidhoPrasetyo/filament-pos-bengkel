<?php

namespace App\Filament\Clusters\Reports\Resources\PurchaseItems\Pages;

use App\Filament\Clusters\Reports\Resources\PurchaseItems\PurchaseItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseItem extends CreateRecord
{
    protected static string $resource = PurchaseItemResource::class;
}
