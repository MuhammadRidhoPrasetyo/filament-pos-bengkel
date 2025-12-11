<?php

namespace App\Filament\Clusters\Reports\Resources\TransactionItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TransactionItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('transaction_id')
                    ->required(),
                TextInput::make('product_id')
                    ->required(),
                TextInput::make('store_id')
                    ->required(),
                TextInput::make('product_stock_id')
                    ->default(null),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('item_discount_mode')
                    ->options(['percent' => 'Percent', 'amount' => 'Amount'])
                    ->default(null),
                TextInput::make('item_discount_value')
                    ->numeric()
                    ->default(null),
                TextInput::make('item_discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('final_unit_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('line_subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('line_total')
                    ->required()
                    ->numeric(),
                TextInput::make('discount_type_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('unit_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('line_cost_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('line_profit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('price_edited')
                    ->required(),
                TextInput::make('pricing_mode')
                    ->default(null),
            ]);
    }
}
