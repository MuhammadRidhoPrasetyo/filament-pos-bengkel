<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->required(),
                TextInput::make('store_id')
                    ->required(),
                TextInput::make('customer_id')
                    ->required(),
                Select::make('status')
                    ->options([
            'checkin' => 'Checkin',
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
                Textarea::make('general_complaint')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('estimated_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('transaction_id')
                    ->default(null),
            ]);
    }
}
