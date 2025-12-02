<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Pages;

use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\Services\Resources\ServiceOrders\ServiceOrderResource;

class CreateServiceOrder extends CreateRecord
{
    protected static string $resource = ServiceOrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['number'] = $number = 'SO-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));

        return $data;
    }
}
