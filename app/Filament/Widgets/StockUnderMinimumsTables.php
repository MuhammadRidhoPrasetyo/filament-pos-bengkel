<?php

namespace App\Filament\Widgets;

use App\Models\ProductStock;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class StockUnderMinimumsTables extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Stok di Bawah Minimum')
            ->description('Daftar produk yang stoknya berada di bawah atau sama dengan stok minimum yang ditetapkan.')
            ->deferLoading()
            ->query(
                fn(): Builder => ProductStock::query()
                    ->whereRelation('product.productCategory', 'item_type', 'part')
                    ->where('store_id', auth()->user()->store_id)
                    ->whereColumn('quantity', '<=', 'minimum_stock')
                    ->where('quantity', '>', 0)
            )
            ->columns([
                TextColumn::make('product.productLabel.display_name')
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
