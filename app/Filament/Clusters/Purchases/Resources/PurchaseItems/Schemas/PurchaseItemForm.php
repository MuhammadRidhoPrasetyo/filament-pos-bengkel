<?php

namespace App\Filament\Clusters\Purchases\Resources\PurchaseItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('purchase_id')
                    ->required(),
                TextInput::make('product_id')
                    ->required(),
                Select::make('price_type')
                    ->options(['toko' => 'Toko', 'distributor' => 'Distributor'])
                    ->default('toko')
                    ->required(),
                TextInput::make('quantity_ordered')
                    ->required()
                    ->numeric(),
                TextInput::make('unit_purchase_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('item_discount_type')
                    ->options(['percent' => 'Percent', 'amount' => 'Amount'])
                    ->default(null),
                TextInput::make('item_discount_value')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
