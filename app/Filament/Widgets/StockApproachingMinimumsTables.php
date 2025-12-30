<?php

namespace App\Filament\Widgets;

use App\Models\ProductStock;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class StockApproachingMinimumsTables extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        $nearPct = 0.25;
        return $table
            ->heading('Stok Mendekati Minimum')
            ->description('Daftar produk yang stoknya mendekati stok minimum yang ditetapkan.')
            ->deferLoading()
            ->query(
                fn(): Builder => ProductStock::query()
                    ->where('store_id', auth()->user()->store_id)
                    ->whereNotNull('minimum_stock')
                    ->where('minimum_stock', '>', 0)
                    ->whereColumn('quantity', '>', 'minimum_stock')
                    ->whereRaw('quantity <= minimum_stock + (minimum_stock * ?)', [$nearPct])
            )
            ->columns([
                TextColumn::make('product.label')
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
