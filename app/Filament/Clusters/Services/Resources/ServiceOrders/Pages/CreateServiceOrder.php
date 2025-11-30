<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Pages;

use App\Filament\Clusters\Services\Resources\ServiceOrders\ServiceOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceOrder extends CreateRecord
{
    protected static string $resource = ServiceOrderResource::class;
}
