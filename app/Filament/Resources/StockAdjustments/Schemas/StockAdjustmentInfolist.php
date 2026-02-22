<?php

namespace App\Filament\Resources\StockAdjustments\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

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
                                Section::make('Rincian Penyesuaian Stok')
                                    ->icon(LucideIcon::TrendingUp)
                                    ->description('Daftar produk yang disesuaikan stoknya, mencakup tipe penyesuaian (masuk/keluar) dan keterangan.')
                                    ->columns(12)
                                    ->columnSpanFull()
                                    ->schema([
                                        RepeatableEntry::make('items')
                                            ->hiddenLabel()
                                            ->columns(12)
                                            ->columnSpanFull()
                                            ->table([
                                                TableColumn::make('Produk'),
                                                TableColumn::make('Tipe'),
                                                TableColumn::make('Jumlah'),
                                                TableColumn::make('Catatan'),
                                            ])
                                            ->schema([
                                                TextEntry::make('product.name')
                                                    ->columnSpan(3)
                                                    ->label('Produk')
                                                    ->weight('semibold'),
                                                TextEntry::make('adjustment_type')
                                                    ->formatStateUsing(fn(string $state): string => $state == 'in' ? 'Masuk ⬆️' : 'Keluar ⬇️')
                                                    ->columnSpan(3)
                                                    ->label('Tipe')
                                                    ->badge()
                                                    ->color(fn(string $state): string => str_contains($state, 'Masuk') ? 'success' : 'danger'),
                                                TextEntry::make('quantity')
                                                    ->columnSpan(3)
                                                    ->label('Jumlah')
                                                    ->weight('semibold'),
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

                                Section::make('Informasi Penyesuaian')
                                    ->icon(LucideIcon::Gauge)
                                    ->description('Detail dokumen penyesuaian stok untuk audit dan pelacakan.')
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 4,
                                        'lg' => 4,
                                    ])
                                    ->schema([
                                        TextEntry::make('store.name')
                                            ->label('Bengkel')
                                            ->badge()
                                            ->color('warning'),
                                        TextEntry::make('postedBy.name')
                                            ->label('Dibuat Oleh')
                                            ->icon('heroicon-o-user'),
                                        TextEntry::make('reference_number')
                                            ->label('Nomor Referensi')
                                            ->badge()
                                            ->color('info')
                                            ->copyable(),
                                        TextEntry::make('occurred_at')
                                            ->label('Tanggal Penyesuaian')
                                            ->dateTime(),
                                        TextEntry::make('note')
                                            ->label('Catatan')
                                            ->columnSpanFull(),
                                    ]),

                            ]),

                    ]),

            ]);
    }
}
