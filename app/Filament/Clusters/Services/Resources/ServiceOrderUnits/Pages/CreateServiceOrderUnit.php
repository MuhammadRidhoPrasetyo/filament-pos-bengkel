<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Pages;

use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\ServiceOrderUnitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceOrderUnit extends CreateRecord
{
    protected static string $resource = ServiceOrderUnitResource::class;
}
