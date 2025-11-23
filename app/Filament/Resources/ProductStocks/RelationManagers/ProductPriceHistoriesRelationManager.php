<?php

namespace App\Filament\Resources\ProductStocks\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ProductStocks\ProductStockResource;

class ProductPriceHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'productPriceHistories';
    protected static ?string $title = 'Riwayat Harga';
    protected static ?string $pluralLabel = 'Riwayat Harga';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->label('Tanggal'),
                TextColumn::make('productPrice.price_type')
                    ->label('Tipe Harga'),
                TextColumn::make('productPrice.purchase_price')
                    ->label('Harga Beli'),
                TextColumn::make('productPrice.markup')
                    ->label('Markup'),
                TextColumn::make('productPrice.selling_price')
                    ->label('Harga Jual'),
            ])
            ->headerActions([
                //
            ]);
    }
}
