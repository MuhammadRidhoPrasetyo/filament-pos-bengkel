<?php

namespace App\Filament\Resources\ProductStocks\RelationManagers;

use App\Filament\Resources\ProductStocks\ProductStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchaseItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseItems';

    protected static ?string $title = 'Riwayat Barang Masuk';
    protected static ?string $pluralLabel = 'Riwayat Barang Masuk';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('purchase.invoice_number')
                    ->label('Nomor Faktur'),
                TextColumn::make('product.name')
                    ->label('Produk'),
                TextColumn::make('quantity_ordered')
                    ->label('Jumlah'),
                TextColumn::make('unit_purchase_price')
                    ->label('Harga Beli'),
                TextColumn::make('item_discount_value')
                    ->label('Diskon'),
                TextColumn::make('item_discount_type')
                    ->label('Tipe Diskon'),
                TextColumn::make('total_price')
                    ->label('Total Harga'),

            ])
            ->headerActions([
                // CreateAction::make(),
            ]);
    }
}
