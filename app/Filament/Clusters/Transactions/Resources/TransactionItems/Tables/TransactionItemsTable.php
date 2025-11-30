<?php

namespace App\Filament\Clusters\Transactions\Resources\TransactionItems\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class TransactionItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('transaction.number')
                    ->label('Transaksi')
                    ->searchable(),
                TextColumn::make('store.name')
                    ->label('Bengkel')
                    ->searchable(),
                TextColumn::make('productStock.product.name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Harga')
                    ->money()
                    ->sortable(),
                TextColumn::make('item_discount_mode')
                    ->label('Jenis Diskon')
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
                    ->label('Harga Akhir')
                    ->money()
                    ->sortable(),
                TextColumn::make('line_subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('line_total')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discountType.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_cost')
                    ->label('Harga Beli')
                    ->money()
                    ->sortable(),
                TextColumn::make('line_cost_total')
                    ->label('Total Harga Beli')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('line_profit')
                    ->label('Keuntungan')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('price_edited')
                    ->label('Harga Berubah')
                    ->boolean(),
                TextColumn::make('pricing_mode')
                    ->label('Pengaturan Harga')
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
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('Export'),
                ]),
            ]);
    }
}
