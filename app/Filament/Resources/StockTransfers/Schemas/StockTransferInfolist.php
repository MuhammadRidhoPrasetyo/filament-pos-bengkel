<?php

namespace App\Filament\Resources\StockTransfers\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class StockTransferInfolist
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
                                Section::make('Transfer Items')
                                    ->icon(LucideIcon::Package)
                                    ->description('Daftar produk yang akan dipindahkan antar bengkel. Periksa jumlah agar tidak melebihi stok bengkel asal.')
                                    ->columns(12)
                                    ->columnSpanFull()
                                    ->schema([
                                        RepeatableEntry::make('items')
                                            ->hiddenLabel()
                                            ->columns(12)
                                            ->columnSpanFull()
                                            ->table([
                                                \Filament\Infolists\Components\RepeatableEntry\TableColumn::make('Produk'),
                                                \Filament\Infolists\Components\RepeatableEntry\TableColumn::make('Jumlah'),
                                            ])
                                            ->schema([
                                                TextEntry::make('product.label')
                                                    ->label('Produk')
                                                    ->columnSpan(8),
                                                TextEntry::make('quantity')
                                                    ->label('Jumlah')
                                                    ->columnSpan(4),
                                            ])
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
                                    ->icon(LucideIcon::Info)
                                    ->description('Ringkasan transfer: bengkel asal, tujuan, status, dan nomor referensi untuk audit dan pelacakan.')
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 4,
                                        'lg' => 4,
                                    ])
                                    ->schema([
                                        TextEntry::make('fromStore.name')->label('Dari Bengkel'),
                                        TextEntry::make('toStore.name')->label('Ke Bengkel'),
                                        TextEntry::make('status')->label('Status'),
                                        TextEntry::make('reference_number')->label('Nomor Referensi'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
