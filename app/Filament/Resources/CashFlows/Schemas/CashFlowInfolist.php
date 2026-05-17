<?php

namespace App\Filament\Resources\CashFlows\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class CashFlowInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Informasi Transaksi')
                            ->icon(LucideIcon::Banknote)
                            ->description('Detail alur kas masuk atau keluar.')
                            ->columnSpan(8)
                            ->schema([
                                Grid::make(12)->schema([
                                    TextEntry::make('type')
                                        ->label('Tipe')
                                        ->badge()
                                        ->columnSpan(4)
                                        ->formatStateUsing(fn(string $state) => match ($state) {
                                            'income'  => 'Kas Masuk',
                                            'expense' => 'Kas Keluar',
                                            default   => $state,
                                        })
                                        ->color(fn(string $state) => match ($state) {
                                            'income'  => 'success',
                                            'expense' => 'danger',
                                            default   => 'gray',
                                        }),

                                    TextEntry::make('date')
                                        ->label('Tanggal')
                                        ->date('d M Y')
                                        ->icon(LucideIcon::CalendarDays->value)
                                        ->columnSpan(4),

                                    TextEntry::make('amount')
                                        ->label('Jumlah')
                                        ->money('IDR', true)
                                        ->weight('bold')
                                        ->columnSpan(4),
                                ]),

                                Grid::make(12)->schema([
                                    TextEntry::make('store.name')
                                        ->label('Bengkel')
                                        ->icon(LucideIcon::Store->value)
                                        ->columnSpan(4),

                                    TextEntry::make('user.name')
                                        ->label('Dicatat Oleh')
                                        ->icon(LucideIcon::User->value)
                                        ->columnSpan(4),

                                    TextEntry::make('category.name')
                                        ->label('Kategori')
                                        ->badge()
                                        ->color('info')
                                        ->columnSpan(4),
                                ]),

                                TextEntry::make('description')
                                    ->label('Keterangan')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Referensi')
                            ->icon(LucideIcon::Link)
                            ->description('Sumber transaksi yang terkait.')
                            ->columnSpan(4)
                            ->schema([
                                TextEntry::make('reference_type')
                                    ->label('Tipe Referensi')
                                    ->placeholder('-')
                                    ->formatStateUsing(fn(?string $state) => $state
                                        ? class_basename($state)
                                        : '-'),

                                TextEntry::make('reference_id')
                                    ->label('ID Referensi')
                                    ->placeholder('-')
                                    ->copyable(),

                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d M Y H:i')
                                    ->placeholder('-'),

                                TextEntry::make('updated_at')
                                    ->label('Diperbarui Pada')
                                    ->dateTime('d M Y H:i')
                                    ->placeholder('-'),
                            ]),
                    ]),
            ]);
    }
}
