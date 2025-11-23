<?php

namespace App\Filament\Resources\ProductStocks\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ProductStocks\ProductStockResource;

class StockAdjustmentItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'stockAdjustmentItems';
    protected static ?string $title = 'Penyesuaian Stok';
    protected static ?string $pluralLabel = 'Penyesuaian Stok';

    // protected static ?string $relatedResource = ProductStockResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('adjustment_type')
                    ->label('Tipe Penyesuaian')
                    ->formatStateUsing(fn ($state) => $state == 'in' ? 'Masuk' : 'Keluar'),
                TextColumn::make('quantity')
                    ->label('Jumlah Penyesuaian'),
                TextColumn::make('note')
                    ->label('Keterangan'),
            ])
            ->headerActions([
                // CreateAction::make(),
            ]);
    }
}
