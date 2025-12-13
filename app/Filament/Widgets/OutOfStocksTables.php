<?php

namespace App\Filament\Widgets;

use App\Models\ProductStock;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class OutOfStocksTables extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;
    public function table(Table $table): Table
    {
        return $table
            ->heading('Stok Habis')
            ->description('Daftar produk yang stoknya habis.')
            ->deferLoading()
            ->query(
                fn(): Builder => ProductStock::query()
                    ->whereRelation('product.productCategory', 'item_type', 'part')
                    ->where('store_id', auth()->user()->store_id)
                    ->where('quantity', 0)
            )
            ->columns([
                TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable(),
                TextColumn::make('product.brand.name')
                    ->label('Merek Produk')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Jumlah Stok')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->numeric()
                    ->sortable(),
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
