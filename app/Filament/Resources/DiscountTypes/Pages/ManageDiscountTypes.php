<?php

namespace App\Filament\Resources\DiscountTypes\Pages;

use App\Filament\Resources\DiscountTypes\DiscountTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDiscountTypes extends ManageRecords
{
    protected static string $resource = DiscountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
