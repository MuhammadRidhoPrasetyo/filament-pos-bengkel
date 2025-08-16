<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Forms\Components\Repeater\TableColumn;

class PurchaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([

                        Grid::make()
                            ->columns(8)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                                'xl' => 8,
                            ])
                            ->schema([

                                Section::make('Barang Masuk')
                                    ->columns(12)
                                    ->columnSpanFull()
                                    ->schema([
                                        RepeatableEntry::make('items')
                                        ->hiddenLabel()
                                            ->schema([
                                                TextEntry::make('product.name')
                                                    ->columnSpan(2)
                                                    ->label('Produk'),
                                                TextEntry::make('price_type')
                                                    ->columnSpan(2)
                                                    ->label('Tipe Harga'),
                                                TextEntry::make('quantity_ordered')
                                                    ->columnSpan(2)
                                                    ->label('Jumlah Beli'),
                                                TextEntry::make('unit_purchase_price')
                                                    ->columnSpan(2)
                                                    ->label('Harga Beli'),
                                                TextEntry::make('item_discount_type')
                                                    ->columnSpan(2)
                                                    ->label('Jenis Diskon'),
                                                TextEntry::make('item_discount_value')
                                                    ->columnSpan(2)
                                                    ->label('Nilai Diskon'),
                                            ])
                                            ->columns(12)
                                            ->columnSpanFull()

                                    ]),


                            ]),

                        Grid::make()
                            ->columns(4)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 4,
                                'lg' => 4,
                                'xl' => 4,
                            ])
                            ->schema([

                                Section::make('Details')
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 4,
                                        'lg' => 4,
                                    ]) // <== lebar 8/12
                                    ->schema([
                                        TextEntry::make('store.name')
                                            ->label('Bengkel'),

                                        TextEntry::make('invoice_number')
                                            ->label('Nomor Invoice/Nota Supplier'),
                                        TextEntry::make('purchase_date')
                                            ->label('Tanggal Pembelian')
                                            ->date(),
                                        TextEntry::make('discount_type')
                                            ->label('Jenis Diskon'),
                                        TextEntry::make('discount_value')
                                            ->label('Nilai Diskon')
                                            ->numeric(),
                                        TextEntry::make('price')
                                            ->label('Total Pembelian')
                                            ->money('Rp.'),
                                    ]),


                                Section::make('Details')
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 4,
                                        'lg' => 4,
                                    ]) // <== lebar 8/12
                                    ->schema([
                                        TextEntry::make('supplier.name')
                                            ->label('Supplier'),
                                        TextEntry::make('createdBy.name')
                                            ->label('Dibuat Oleh'),
                                        TextEntry::make('receivedBy.name')
                                            ->label('Diterima Oleh'),
                                    ]),
                            ]),

                    ]),
            ]);
    }
}
