<?php

namespace App\Filament\Resources\ProductStocks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class ProductStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Informasi Stok')
                            ->icon(LucideIcon::Package)
                            ->description('Kelola jumlah stok dan stok minimum untuk memastikan ketersediaan produk.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextInput::make('product_id')
                                    ->hidden()
                                    ->required(),
                                TextInput::make('store_id')
                                    ->required()
                                    ->hidden(),
                                TextInput::make('quantity')
                                    ->label('Jumlah Stok Saat Ini')
                                    ->required()
                                    ->numeric()
                                    ->columnSpanFull()
                                    ->disabled()
                                    ->helperText('Jumlah stok tidak dapat diedit langsung; gunakan Adjustment atau Transfer untuk mengubah stok.'),
                                TextInput::make('minimum_stock')
                                    ->label('Stok Minimum')
                                    ->required()
                                    ->numeric()
                                    ->columnSpanFull()
                                    ->helperText('Sistem akan memberi notifikasi ketika stok di bawah nilai minimum.'),
                                TextInput::make('product_price_id')
                                    ->hidden()
                                    ->default(null),
                            ]),
                    ]),
            ]);
    }
}
