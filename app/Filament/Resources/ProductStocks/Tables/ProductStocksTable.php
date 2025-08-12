<?php

namespace App\Filament\Resources\ProductStocks\Tables;

use App\Models\Store;
use App\Models\Product;
use Filament\Tables\Table;
use App\Models\ProductStock;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->hidden()
                    ->rowIndex()
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable(),
                TextColumn::make('store.name')
                    ->label('Bengkel')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('productPrice.purchase_price')
                    ->label('Harga Beli')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('productPrice.markup')
                    ->label('Markup')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('productPrice.selling_price')
                    ->label('Harga Produk')
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
                SelectFilter::make('store_id')
                    ->options(Store::all()
                        ->when(Auth::user()->store_id !== null, function ($query) {
                            return $query->where('id', Auth::user()->store_id);
                        })
                        ->pluck('id', 'name'))
                    ->relationship('store', 'name')
                    ->label('Bengkel'),

                SelectFilter::make('product_id')
                    ->options(Product::all()->pluck('id', 'name'))
                    ->relationship('product', 'name')
                    ->label('Produk'),

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
