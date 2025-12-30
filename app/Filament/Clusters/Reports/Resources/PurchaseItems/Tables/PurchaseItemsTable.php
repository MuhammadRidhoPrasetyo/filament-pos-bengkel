<?php

namespace App\Filament\Clusters\Reports\Resources\PurchaseItems\Tables;

use App\Models\Store;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class PurchaseItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Laporan Item Pembelian')
            ->description('Lihat dan telusuri semua item pembelian: nomor invoice, produk, tipe harga, jumlah, dan nilai diskon. Gunakan filter untuk memfokuskan hasil berdasarkan tanggal atau supplier untuk audit dan rekonsiliasi stok.')
            ->columns([
                TextColumn::make('purchase.invoice_number')
                    ->label('Invoice')
                    ->searchable(),
                TextColumn::make('product.label')
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
                Filter::make('store_id')
                    ->schema([
                        Select::make('store_id')
                            ->options(
                                Store::pluck('name', 'id')->toArray()
                            )
                            ->label('Bengkel')
                            ->searchable(),

                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['store_id'],
                                fn(Builder $query, $cashierId): Builder => $query->whereRelation('purchase', 'store_id', $data['store_id']),
                            );
                    })
                    ->columnSpanFull(),

                Filter::make('from')
                    ->schema([
                        DatePicker::make('from')->label('Dari'),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereRelation('purchase', 'purchase_date', '>=', $date),
                            );
                    }),

                Filter::make('to')
                    ->schema([
                        DatePicker::make('to')->label('Sampai'),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query

                            ->when(
                                $data['to'],
                                fn(Builder $query, $date): Builder => $query->whereRelation('purchase', 'purchase_date', '<=', $date),
                            );
                    })
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(2)
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
