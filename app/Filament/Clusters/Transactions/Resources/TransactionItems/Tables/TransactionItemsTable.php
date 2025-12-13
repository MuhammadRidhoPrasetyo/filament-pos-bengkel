<?php

namespace App\Filament\Clusters\Transactions\Resources\TransactionItems\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class TransactionItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                // fn(Builder $query) => $query
                //     ->when(!Auth::user()->hasRole('owner'), function ($query) {
                //         return $query->where('store_id', Auth::user()->store_id);
                //     })
                fn(Builder $query) => $query
                    ->where('store_id', Auth::user()->store_id)
            )
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
                TextColumn::make('productStock.product.productLabel.display_name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('item_discount_mode')
                    ->label('Jenis Diskon')
                    ->badge(),
                TextColumn::make('item_discount_value')
                    ->label('Nilai Diskon')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('item_discount_amount')
                    ->label('Jumlah Diskon')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('final_unit_price')
                    ->label('Harga Akhir')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('line_subtotal')
                    ->label('Subtotal')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('line_total')
                    ->label('Total')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('discountType.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_cost')
                    ->label('Harga Beli')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('line_cost_total')
                    ->label('Total Harga Beli')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('line_profit')
                    ->label('Keuntungan')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
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
