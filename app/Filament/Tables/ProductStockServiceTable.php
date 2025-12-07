<?php

namespace App\Filament\Tables;

use App\Models\Product;
use Filament\Tables\Table;
use App\Models\ProductStock;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ProductStockServiceTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                fn(): Builder => Product::query()
                    ->with([
                        'stock' => function ($query) {
                            $query
                                ->where('is_hidden', false)
                                ->where('store_id', auth()->user()->store_id);
                        },
                        'stock.productPrice',
                        'productCategory',
                        'brand'
                    ])
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                TextColumn::make('stock.quantity')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock.productPrice.selling_price')
                    ->label('Harga')
                    ->searchable(),
                TextColumn::make('productCategory.name')
                    ->label('Kategori Produk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Merk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe Produk')
                    ->searchable(),
                TextColumn::make('keyword')
                    ->label('Kata Kunci')
                    ->searchable(),
                TextColumn::make('compatibility')
                    ->label('Kompatibilitas')
                    ->searchable(),
                TextColumn::make('size')
                    ->label('Ukuran')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
