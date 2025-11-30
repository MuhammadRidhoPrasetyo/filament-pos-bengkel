<?php

namespace App\Filament\Clusters\Purchases\Resources\PurchaseItems\Pages;

use App\Filament\Clusters\Purchases\Resources\PurchaseItems\PurchaseItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseItem extends CreateRecord
{
    protected static string $resource = PurchaseItemResource::class;
}
