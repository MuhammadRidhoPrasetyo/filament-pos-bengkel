<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceOrderUnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('service_order_id'),
                TextEntry::make('customer_vehicle_id')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('checkin_at')
                    ->dateTime(),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('complaint')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('diagnosis')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('work_done')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('estimated_total')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('plate_number'),
                TextEntry::make('brand')
                    ->placeholder('-'),
                TextEntry::make('model')
                    ->placeholder('-'),
                TextEntry::make('year')
                    ->placeholder('-'),
                TextEntry::make('color')
                    ->placeholder('-'),
            ]);
    }
}
