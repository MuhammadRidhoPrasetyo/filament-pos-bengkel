<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceOrderUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service_order_id')
                    ->required(),
                TextInput::make('customer_vehicle_id')
                    ->default(null),
                Select::make('status')
                    ->options([
            'checkin' => 'Checkin',
            'diagnosis' => 'Diagnosis',
            'in_progress' => 'In progress',
            'waiting_parts' => 'Waiting parts',
            'ready' => 'Ready',
            'invoiced' => 'Invoiced',
            'cancelled' => 'Cancelled',
        ])
                    ->default('checkin')
                    ->required(),
                DateTimePicker::make('checkin_at')
                    ->required(),
                DateTimePicker::make('completed_at'),
                Textarea::make('complaint')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('diagnosis')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('work_done')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('estimated_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('plate_number')
                    ->required(),
                TextInput::make('brand')
                    ->default(null),
                TextInput::make('model')
                    ->default(null),
                TextInput::make('year')
                    ->default(null),
                TextInput::make('color')
                    ->default(null),
            ]);
    }
}
