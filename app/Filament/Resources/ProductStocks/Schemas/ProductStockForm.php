<?php

namespace App\Filament\Resources\ProductStocks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_id')
                    ->hidden()
                    ->required(),
                TextInput::make('store_id')
                    ->required()
                    ->hidden(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->columnSpanFull()
                    ->disabled(),
                TextInput::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->required()
                    ->numeric()
                    ->columnSpanFull(),
                TextInput::make('product_price_id')
                    ->hidden()
                    ->default(null),
            ]);
    }
}
