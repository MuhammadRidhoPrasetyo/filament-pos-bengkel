<?php

namespace App\Filament\Resources\StockAdjustments\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class StockAdjustmentInfolist
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
                                Section::make('Penyesuaian Stok')
                                    ->columns(12)
                                    ->columnSpanFull()
                                    ->schema([
                                        RepeatableEntry::make('items')
                                            ->hiddenLabel()
                                            ->columns(12)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextEntry::make('product.name')
                                                    ->columnSpan(3)
                                                    ->label('Produk'),
                                                TextEntry::make('adjustment_type')
                                                ->formatStateUsing(fn (string $state) : string => $state == 'in' ? 'Masuk' : 'Keluar')
                                                    ->columnSpan(3)
                                                    ->label('Tipe'),
                                                TextEntry::make('quantity')
                                                    ->columnSpan(3)
                                                    ->label('Jumlah'),
                                                TextEntry::make('note')
                                                    ->columnSpan(3)
                                                    ->label('Catatan'),
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
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 4,
                                        'lg' => 4,
                                    ]) // <== lebar 8/12
                                    ->schema([
                                        TextEntry::make('store.name')
                                            ->label('Bengkel'),
                                        TextEntry::make('postedBy.name')
                                            ->label('Dibuat Oleh'),
                                        TextEntry::make('reference_number')
                                            ->label('Nomor Referensi'),
                                        TextEntry::make('occurred_at')
                                            ->label('Tanggal')
                                            ->dateTime(),
                                        TextEntry::make('note')
                                            ->label('Catatan'),
                                    ]),

                            ]),

                    ]),

            ]);
    }
}
