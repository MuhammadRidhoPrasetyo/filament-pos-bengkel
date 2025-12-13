<?php

namespace App\Filament\Clusters\Purchases\Resources\PurchaseItems\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class PurchaseItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                // fn(Builder $query) => $query
                //     ->when(!Auth::user()->hasRole('owner'), function ($query) {
                //         $query->whereRelation('purchase', 'store_id', Auth::user()->store_id);
                //     })
                fn(Builder $query) => $query
                    ->whereRelation('purchase', 'store_id', Auth::user()->store_id)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('purchase.invoice_number')
                    ->label('Invoice')
                    ->searchable(),
                TextColumn::make('product.productLabel.display_name')
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
                    ->money('Rp.')
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
