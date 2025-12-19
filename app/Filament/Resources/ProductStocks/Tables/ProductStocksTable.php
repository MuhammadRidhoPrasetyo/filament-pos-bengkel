<?php

namespace App\Filament\Resources\ProductStocks\Tables;

use App\Models\Store;
use App\Models\Product;
use Filament\Tables\Table;
use App\Models\ProductStock;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Forms\Components\TextInput;

class ProductStocksTable
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
                    ->hidden()
                    ->rowIndex()
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('product.label')
                    ->label('Nama Produk'),
                TextColumn::make('store.name')
                    ->label('Bengkel')
                    ->searchable(),
                TextColumn::make('product.brand.name')
                    ->label('Merk')
                    ->searchable(),
                TextColumn::make('product.compatibility')
                    ->label('Kompatibilitas')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),

                ToggleColumn::make('is_hidden')
                    ->label('Sembunyikan Dari Kasir')
                    ->visible(fn() => Auth::user()->hasRole('owner'))
                    ->sortable(),

                TextColumn::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('productPrice.purchase_price')
                    ->label('Harga Beli')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->numeric()
                    ->sortable(),

                TextColumn::make('productPrice.markup')
                    ->label('Markup')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('productPrice.selling_price')
                    ->label('Harga Produk')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->hidden()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->hidden()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('product')
                    ->label('Produk')
                    ->schema([
                        TextInput::make('product')
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['product'],
                                fn(Builder $query, $product): Builder => $query
                                    ->withWhereHas('product', function ($q) use ($product) {
                                        $q->where(function ($q) use ($product) {
                                            $q->whereAny(['name', 'sku', 'keyword', 'compatibility'], 'like', "%$product%");
                                        })
                                            ->orWhere(function ($q) use ($product) {
                                                $q->whereRelation('brand', 'name', 'like', "%$product%");
                                            });
                                    })
                            );
                    })
                    ->columnSpanFull(),

            ],   layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(1)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('export')
                ]),
            ]);
    }
}
