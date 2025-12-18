<?php

namespace App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class ServicesPerMechanicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction.number')
                    ->label('Nomor Transaksi')
                    ->searchable(),
                TextColumn::make('product')
                    ->label('Nama Produk')
                    ->formatStateUsing(fn ($state, $record) => $record->product?->productLabel?->display_name ?? $record->product?->name)
                    ->searchable(),
                TextColumn::make('store.name')
                    ->label('Nama Bengkel')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Kuantitas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Harga Satuan')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('item_discount_mode')
                    ->label('Tipe Diskon')
                    ->badge(),
                TextColumn::make('item_discount_value')
                    ->label('Nilai Diskon')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('item_discount_amount')
                    ->label('Jumlah Diskon')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('final_unit_price')
                    ->label('Harga Satuan Akhir')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('line_subtotal')
                    ->label('Subtotal')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('line_total')
                    ->label('Total')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discountType.name')
                    ->label('Tipe Diskon Promo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_cost')
                    ->label('Harga Pokok Satuan')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('line_cost_total')
                    ->label('Total Harga Pokok')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('line_profit')
                    ->label('Laba')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->numeric()
                    ->sortable(),
                IconColumn::make('price_edited')
                    ->label('Harga Diubah')
                    ->boolean(),
                TextColumn::make('pricing_mode')
                    ->label('Mode Penetapan Harga')
                    ->formatStateUsing(fn($state) => $state === 'editable' ? 'Dapat Diubah' : 'Tetap')
                    ->searchable(),
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
