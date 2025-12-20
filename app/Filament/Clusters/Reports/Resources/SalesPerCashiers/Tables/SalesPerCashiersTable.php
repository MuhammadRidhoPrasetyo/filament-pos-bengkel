<?php

namespace App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Tables;

use App\Models\User;
use App\Models\Store;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class SalesPerCashiersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Penjualan per Kasir')
            ->description('Analisis cepat performa kasir: lihat transaksi, produk terlaris, dan kontribusi pendapatan per kasir. Terapkan filter tanggal atau kasir untuk menyaring hasil dan temukan insight penjualan dengan mudah.')
            ->columns([
                TextColumn::make('transaction.number')
                    ->label('Nomor Transaksi')
                    ->searchable(),
                TextColumn::make('product.productLabel.display_name')
                    ->label('Nama Produk')
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

                Filter::make('cashier_id')
                    ->schema([
                        Select::make('cashier_id')
                            ->options(
                                User::query()
                                    ->whereRelation('roles', 'name', 'cashier')
                                    ->pluck('name', 'id')->toArray()
                            )
                            ->label('Kasir')
                            ->searchable(),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['cashier_id'],
                                fn(Builder $query, $cashierId): Builder => $query->whereRelation('transaction', 'user_id', $data['cashier_id']),
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
                                fn(Builder $query, $date): Builder => $query->whereRelation('transaction', 'transaction_date', '>=', $date),
                            );
                    }),

                Filter::make('to')
                    ->schema([
                        DatePicker::make('to')->label('Sampai'),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query

                            ->when(
                                $data['to'],
                                fn(Builder $query, $date): Builder => $query->whereRelation('transaction', 'transaction_date', '<=', $date),
                            );
                    })

            ],  layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(2)
            ->paginationPageOptions([10, 25, 50, 100, 200])
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
