<?php

namespace App\Filament\Resources\Stores\Schemas;

use Carbon\Carbon;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;

class StoreInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(12)
                    ->columnSpan(12)
                    ->schema([

                        // =======================
                        // 1. PROFIL BENGKEL
                        // =======================
                        Section::make('Profil Bengkel')
                            ->icon('heroicon-o-building-storefront')
                            ->description('Informasi umum bengkel / toko.')
                            ->columns(12)
                            ->columnSpan(8)
                            ->schema([

                                TextEntry::make('code')
                                    ->label('Kode Bengkel')
                                    ->badge()
                                    ->weight('semibold')
                                    ->columnSpan(3),

                                TextEntry::make('name')
                                    ->label('Nama Bengkel')
                                    ->weight('semibold')
                                    ->columnSpan(3),

                                TextEntry::make('phone')
                                    ->label('Nomor Handphone')
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->copyMessage('Nomor handphone disalin.')
                                    ->columnSpan(3),

                                TextEntry::make('email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->copyMessage('Email disalin.')
                                    ->columnSpan(3),

                                TextEntry::make('address')
                                    ->label('Alamat')
                                    ->icon('heroicon-o-map')
                                    ->columnSpan(3),


                                TextEntry::make('city')
                                    ->label('Kota')
                                    ->columnSpan(3),

                                TextEntry::make('province')
                                    ->label('Provinsi')
                                    ->columnSpan(3),


                                TextEntry::make('postal_code')
                                    ->label('Kode Pos')
                                    ->columnSpan(3),


                            ]),

                        // =======================
                        // 3. LAIN-LAIN (opsional)
                        // =======================
                        Section::make('Pengaturan Nomor Struk')
                            ->icon('heroicon-o-document-text')
                            ->description('Format dan konfigurasi penomoran struk untuk toko ini.')
                            ->collapsed()
                            ->columns(12)
                            ->columnSpan(4)
                            ->schema([
                                TextEntry::make('receipt_number_format')
                                    ->label('Format Nomor Struk')
                                    ->hint('{STORE_CODE}, {YYYY}, {MM}, {DD}, {NUMBER}')
                                    ->columnSpanFull()
                                    ->copyable(),

                                TextEntry::make('receipt_sequence_year')
                                    ->label('Tahun Sequence Aktif')
                                    ->columnSpanFull()
                                    ->placeholder('-'),

                                TextEntry::make('receipt_sequence')
                                    ->label('Nomor Terakhir')
                                    ->numeric()
                                    ->columnSpanFull()
                                    ->placeholder('0'),
                            ]),

                    ]),

                Grid::make(12)
                    ->columnSpan(12)
                    ->schema([
                        // =======================
                        // 2. RINGKASAN KEUANGAN
                        // =======================
                        Section::make('Ringkasan Keuangan')
                            ->icon('heroicon-o-banknotes')
                            ->description('Omzet dan pengeluaran berdasarkan periode.')
                            ->columns(12)
                            ->columnSpanFull()
                            ->schema([
                                // Baris: Hari ini
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('omzet_today')
                                            ->label('Omzet Hari Ini')
                                            ->state(function ($record) {
                                                $today = Carbon::today();
                                                return $record->transactions()
                                                    ->whereDate('transaction_date', $today)
                                                    ->sum('grand_total');
                                            })
                                            ->money('IDR', locale: 'id')
                                            ->color('success')
                                            ->weight('semibold'),

                                        TextEntry::make('expense_today')
                                            ->label('Pengeluaran Hari Ini')
                                            ->state(function ($record) {
                                                $today = Carbon::today();
                                                return $record->purchases()
                                                    ->whereDate('purchase_date', $today)
                                                    ->sum('price');
                                            })
                                            ->money('IDR', locale: 'id')
                                            ->color('danger')
                                            ->weight('semibold'),

                                        TextEntry::make('profit_today')
                                            ->label('Profit Hari Ini')
                                            ->state(fn($record) => (
                                                ($record->transactions()
                                                    ->whereDate('transaction_date', Carbon::today())
                                                    ->sum('grand_total'))
                                                -
                                                ($record->purchases()
                                                    ->whereDate('purchase_date', Carbon::today())
                                                    ->sum('price'))
                                            ))
                                            ->money('IDR', locale: 'id')
                                            ->color(fn($state) => $state >= 0 ? 'success' : 'danger')
                                            ->weight('bold'),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull(),

                                // Baris: Bulan ini
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('omzet_month')
                                            ->label('Omzet Bulan Ini')
                                            ->state(function ($record) {
                                                $start = Carbon::now()->startOfMonth();
                                                $end   = Carbon::now()->endOfMonth();

                                                return $record->transactions()
                                                    ->whereBetween('transaction_date', [$start, $end])
                                                    ->sum('grand_total');
                                            })
                                            ->money('IDR', locale: 'id')
                                            ->color('success'),

                                        TextEntry::make('expense_month')
                                            ->label('Pengeluaran Bulan Ini')
                                            ->state(function ($record) {
                                                $start = Carbon::now()->startOfMonth();
                                                $end   = Carbon::now()->endOfMonth();

                                                return $record->purchases()
                                                    ->whereBetween('purchase_date', [$start, $end])
                                                    ->sum('price');
                                            })
                                            ->money('IDR', locale: 'id')
                                            ->color('danger'),

                                        TextEntry::make('profit_month')
                                            ->label('Profit Bulan Ini')
                                            ->state(function ($record) {
                                                $start = Carbon::now()->startOfMonth();
                                                $end   = Carbon::now()->endOfMonth();

                                                $omzet = $record->transactions()
                                                    ->whereBetween('transaction_date', [$start, $end])
                                                    ->sum('total_profit');

                                                $expense = $record->purchases()
                                                    ->whereBetween('purchase_date', [$start, $end])
                                                    ->sum('price');

                                                return $omzet - $expense;
                                            })
                                            ->money('IDR', locale: 'id')
                                            ->color(fn($state) => $state >= 0 ? 'success' : 'danger')
                                            ->weight('medium'),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull(),

                                // Baris: Tahun ini
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('omzet_year')
                                            ->label('Omzet Tahun Ini')
                                            ->state(function ($record) {
                                                $start = Carbon::now()->startOfYear();
                                                $end   = Carbon::now()->endOfYear();

                                                return $record->transactions()
                                                    ->whereBetween('transaction_date', [$start, $end])
                                                    ->sum('grand_total');
                                            })
                                            ->money('IDR', locale: 'id')
                                            ->color('success'),

                                        TextEntry::make('expense_year')
                                            ->label('Pengeluaran Tahun Ini')
                                            ->state(function ($record) {
                                                $start = Carbon::now()->startOfYear();
                                                $end   = Carbon::now()->endOfYear();

                                                return $record->purchases()
                                                    ->whereBetween('purchase_date', [$start, $end])
                                                    ->sum('price');
                                            })
                                            ->money('IDR', locale: 'id')
                                            ->color('danger'),

                                        TextEntry::make('profit_year')
                                            ->label('Profit Tahun Ini')
                                            ->state(function ($record) {
                                                $start = Carbon::now()->startOfYear();
                                                $end   = Carbon::now()->endOfYear();

                                                $omzet = $record->transactions()
                                                    ->whereBetween('transaction_date', [$start, $end])
                                                    ->sum('total_profit');

                                                $expense = $record->purchases()
                                                    ->whereBetween('purchase_date', [$start, $end])
                                                    ->sum('price');

                                                return $omzet - $expense;
                                            })
                                            ->money('IDR', locale: 'id')
                                            ->color(fn($state) => $state >= 0 ? 'success' : 'danger')
                                            ->weight('medium'),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),




            ]);
    }
}
