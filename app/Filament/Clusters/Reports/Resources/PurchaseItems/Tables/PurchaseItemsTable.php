<?php

namespace App\Filament\Clusters\Reports\Resources\PurchaseItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchaseItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('purchase.invoice_number')
                    ->label('Invoice')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('price_type')
                    ->label('Harga')
                    ->badge(),
                TextColumn::make('quantity_ordered')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_purchase_price')
                    ->label('Harga Beli')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('item_discount_type')
                    ->label('Diskon')
                    ->badge(),
                TextColumn::make('item_discount_value')
                    ->label('Jumlah Diskon')

                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
