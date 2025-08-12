<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Brand;
use Filament\Tables\Table;
use App\Models\ProductCategory;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
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
                TextColumn::make('name')
                    ->label('Nama Produk')
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
                TextColumn::make('created_at')
                    ->hidden()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->hidden()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product_category_id')
                    ->options(ProductCategory::all()->pluck('name', 'id')),

                SelectFilter::make('brand_id')
                    ->options(Brand::all()->pluck('name', 'id')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
