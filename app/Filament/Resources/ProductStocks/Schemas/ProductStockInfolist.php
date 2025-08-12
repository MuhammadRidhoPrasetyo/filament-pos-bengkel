<?php

namespace App\Filament\Resources\ProductStocks\Schemas;

use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ProductStockInfolist
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
                            ->columns(12)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                                'xl' => 8,
                            ])->schema([
                                Section::make('Details')
                                    ->columns(12)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('product.sku')
                                            ->columnSpanFull()
                                            ->label('SKU'),
                                        TextEntry::make('product.name')
                                            ->columnSpanFull()
                                            ->label('Nama Produk'),
                                        TextEntry::make('product.type')
                                            ->columnSpanFull()
                                            ->label('Tipe Produk'),
                                        TextEntry::make('product.keyword')
                                            ->columnSpanFull()
                                            ->label('Kata Kunci'),
                                        TextEntry::make('product.compatibility')
                                            ->columnSpanFull()
                                            ->label('Kompatibilitas'),
                                        TextEntry::make('product.size')
                                            ->columnSpanFull()
                                            ->label('Ukuran'),
                                    ])
                            ]),

                        Grid::make()
                            // ->relationship('product')
                            ->columns(12)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 4,
                                'lg' => 4,
                                'xl' => 4,
                            ])
                            ->schema([
                                Section::make('Informasi Stock')
                                    ->columns(12)
                                    ->columnSpanFull() // <== lebar 8/12
                                    ->schema([
                                        TextEntry::make('store.name')
                                            ->label('Stok Pada Bengkel')
                                            ->icon(Heroicon::OutlinedBuildingStorefront)
                                            ->iconColor('warning')
                                            ->columnSpan(12),
                                        TextEntry::make('quantity')
                                            ->label('Jumlah')
                                            ->icon(Heroicon::OutlinedBanknotes)
                                            ->iconColor('warning')
                                            ->columnSpan(12),
                                    ]),

                                Section::make('Details')
                                    ->columns(12)
                                    ->columnSpanFull() // <== lebar 8/12
                                    ->schema([
                                        TextEntry::make('product.productCategory.name')
                                            ->label('Kategori Produk')
                                            ->columnSpan(12),
                                        TextEntry::make('product.brand.name')
                                            ->label('Merk')
                                            ->columnSpan(12),
                                        TextEntry::make('product.unit.symbol')
                                            ->label('Satuan')
                                            ->columnSpan(12),
                                    ]),

                                // RepeatableEntry::make('product.discounts')
                                //     ->label('Diskon')
                                //     ->columns(12)
                                //     ->columnSpan(12)
                                //     ->schema([
                                //         TextEntry::make('discountType.name') // Menggunakan kolom pada relasi discounts
                                //             ->label('Jenis Diskon')
                                //             ->columnSpan(4),

                                //         TextEntry::make('type') // Menggunakan kolom pada relasi discounts
                                //             ->label('Diskon')
                                //             ->columnSpan(4),

                                //         TextEntry::make('value') // Menggunakan kolom pada relasi discounts
                                //             ->label('Nilai Diskon')
                                //             ->columnSpan(4),
                                //     ]),
                            ])
                    ]),
            ]);
    }
}
