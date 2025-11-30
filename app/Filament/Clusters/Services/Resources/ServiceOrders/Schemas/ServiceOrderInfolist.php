<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;

class ServiceOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        // ===================================
                        // TAB 1: DETAIL SERVIS (service_orders)
                        // ===================================
                        Tab::make('Detail Servis')
                            ->schema([
                                Section::make('Informasi Kunjungan')
                                    ->description('Data utama kunjungan servis di bengkel.')
                                    ->schema([
                                        Grid::make(12)->schema([
                                            TextEntry::make('number')
                                                ->label('Nomor SO')
                                                ->badge()
                                                ->columnSpan(4),

                                            TextEntry::make('store.name')
                                                ->label('Toko / Cabang')
                                                ->icon('heroicon-m-building-storefront')
                                                ->columnSpan(4),

                                            TextEntry::make('customer.name')
                                                ->label('Pelanggan')
                                                ->icon('heroicon-m-user')
                                                ->columnSpan(4),
                                        ]),

                                        Grid::make(12)->schema([
                                            TextEntry::make('checkin_at')
                                                ->label('Check-in')
                                                ->dateTime('d M Y H:i')
                                                ->icon('heroicon-m-arrow-down-circle')
                                                ->columnSpan(4),

                                            TextEntry::make('completed_at')
                                                ->label('Selesai')
                                                ->dateTime('d M Y H:i')
                                                ->placeholder('Belum selesai')
                                                ->icon('heroicon-m-arrow-up-circle')
                                                ->columnSpan(4),

                                            TextEntry::make('status')
                                                ->badge()
                                                ->label('Status Kunjungan')
                                                ->formatStateUsing(fn(string $state) => match ($state) {
                                                    'checkin'       => 'Check-in',
                                                    'in_progress'   => 'Proses',
                                                    'waiting_parts' => 'Menunggu Part',
                                                    'ready'         => 'Siap Diambil',
                                                    'invoiced'      => 'Sudah Invoice',
                                                    'cancelled'     => 'Batal',
                                                    default         => $state,
                                                })
                                                ->color(fn(string $state) => match ($state) {
                                                    'checkin'       => 'gray',
                                                    'in_progress'   => 'warning',
                                                    'waiting_parts' => 'info',
                                                    'ready'         => 'success',
                                                    'invoiced'      => 'success',
                                                    'cancelled'     => 'danger',
                                                    default         => 'gray',
                                                })
                                                ->columnSpan(4),
                                        ]),
                                    ]),

                                Section::make('Keluhan & Estimasi')
                                    ->description('Keluhan umum dan estimasi biaya kunjungan.')
                                    ->schema([
                                        TextEntry::make('general_complaint')
                                            ->label('Keluhan Umum')
                                            ->placeholder('-')
                                            ->columnSpanFull()
                                            ->markdown()
                                            ->prose(),

                                        Grid::make(12)->schema([
                                            TextEntry::make('estimated_total')
                                                ->label('Estimasi Total')
                                                ->money('IDR', true)
                                                ->columnSpan(4),

                                            TextEntry::make('transaction.number')
                                                ->label('Invoice POS')
                                                ->placeholder('Belum dibuat invoice POS')
                                                ->icon('heroicon-m-receipt-percent')
                                                ->columnSpan(8),
                                        ]),
                                    ]),
                            ]),

                        // ===================================
                        // TAB 2: DETAIL UNIT (service_order_units + mechanics)
                        // ===================================
                        Tab::make('Detail Unit')
                            ->schema([
                                Section::make('Unit yang Diservis')
                                    ->description('Setiap unit mewakili satu motor / kendaraan.')
                                    ->schema([
                                        RepeatableEntry::make('units')
                                            ->label('Daftar Unit')
                                            ->hiddenLabel()
                                            ->contained(false)
                                            ->schema([
                                                Section::make()
                                                    ->hiddenLabel()
                                                    ->schema([
                                                        Grid::make(12)->schema([
                                                            TextEntry::make('plate_number')
                                                                ->label('Nomor Polisi')
                                                                ->badge()
                                                                ->columnSpan(3),

                                                            TextEntry::make('brand')
                                                                ->label('Merek')
                                                                ->columnSpan(3),

                                                            TextEntry::make('model')
                                                                ->label('Model')
                                                                ->columnSpan(3),
                                                            TextEntry::make('color')
                                                                ->label('Warna')
                                                                ->columnSpan(3),
                                                        ]),

                                                        Grid::make(12)->schema([
                                                            TextEntry::make('status')
                                                                ->badge()
                                                                ->label('Status Unit')
                                                                ->formatStateUsing(fn(string $state) => match ($state) {
                                                                    'checkin'       => 'Check-in',
                                                                    'diagnosis'     => 'Diagnosis',
                                                                    'in_progress'   => 'Proses',
                                                                    'waiting_parts' => 'Menunggu Part',
                                                                    'ready'         => 'Siap',
                                                                    'invoiced'      => 'Invoiced',
                                                                    'cancelled'     => 'Batal',
                                                                    default         => $state,
                                                                })
                                                                ->color(fn(string $state) => match ($state) {
                                                                    'checkin'       => 'gray',
                                                                    'diagnosis'     => 'info',
                                                                    'in_progress'   => 'warning',
                                                                    'waiting_parts' => 'info',
                                                                    'ready'         => 'success',
                                                                    'invoiced'      => 'success',
                                                                    'cancelled'     => 'danger',
                                                                    default         => 'gray',
                                                                })
                                                                ->columnSpan(3),

                                                            TextEntry::make('checkin_at')
                                                                ->label('Check-in')
                                                                ->dateTime('d M Y H:i')
                                                                ->columnSpan(3),

                                                            TextEntry::make('completed_at')
                                                                ->label('Selesai')
                                                                ->dateTime('d M Y H:i')
                                                                ->placeholder('Belum selesai')
                                                                ->columnSpan(3),
                                                        ]),

                                                        Section::make('Keluhan & Pekerjaan')
                                                            ->collapsed()
                                                            ->schema([
                                                                Grid::make(12)->schema([
                                                                    TextEntry::make('complaint')
                                                                        ->label('Keluhan')
                                                                        ->placeholder('-')
                                                                        ->columnSpan(4),

                                                                    TextEntry::make('diagnosis')
                                                                        ->label('Diagnosis')
                                                                        ->placeholder('-')
                                                                        ->columnSpan(4),

                                                                    TextEntry::make('work_done')
                                                                        ->label('Pekerjaan Dilakukan')
                                                                        ->placeholder('-')
                                                                        ->columnSpan(4),
                                                                ]),
                                                            ]),

                                                        // Grid::make(12)->schema([
                                                        //     TextEntry::make('estimated_total')
                                                        //         ->label('Estimasi Biaya Unit')
                                                        //         ->money('IDR', true)
                                                        //         ->columnSpan(4),
                                                        // ]),

                                                        Section::make('Mekanik')
                                                            ->description('Mekanik yang mengerjakan unit ini.')
                                                            ->schema([
                                                                RepeatableEntry::make('mechanics')
                                                                    ->label('Daftar Mekanik')
                                                                    ->hiddenLabel()
                                                                    ->schema([
                                                                        Grid::make(12)->schema([
                                                                            TextEntry::make('mechanic.name')
                                                                                ->label('Nama Mekanik')
                                                                                ->icon('heroicon-m-user')
                                                                                ->columnSpan(6),

                                                                            // TextEntry::make('role')
                                                                            //     ->badge()
                                                                            //     ->label('Peran')
                                                                            //     ->formatStateUsing(fn(string $state) => match ($state) {
                                                                            //         'leader'    => 'Leader',
                                                                            //         'assistant' => 'Asisten',
                                                                            //         default     => $state,
                                                                            //     })
                                                                            //     ->color(fn(string $state) => match ($state) {
                                                                            //         'leader'    => 'primary',
                                                                            //         'assistant' => 'gray',
                                                                            //         default     => 'gray',
                                                                            //     })
                                                                            //     ->columnSpan(3),

                                                                            // TextEntry::make('work_portion')
                                                                            //     ->label('Porsi Kerja')
                                                                            //     ->suffix('%')
                                                                            //     ->placeholder('-')
                                                                            //     ->columnSpan(3),
                                                                        ]),
                                                                    ]),
                                                            ]),
                                                    ]),
                                            ]),
                                    ]),
                            ]),

                        // ===================================
                        // TAB 3: PART & JASA (service_order_items)
                        // ===================================
                        Tab::make('Part & Jasa')
                            ->schema([
                                Section::make('Part & Jasa per Unit')
                                    ->description('Rincian part dan jasa yang dikerjakan.')
                                    ->schema([
                                        RepeatableEntry::make('units')
                                            ->label('Unit Servis')
                                            ->hiddenLabel()
                                            ->contained(false)
                                            ->schema([
                                                RepeatableEntry::make('items')
                                                    ->label('Item Part & Jasa')
                                                    ->hiddenLabel()
                                                    ->table([
                                                        TableColumn::make('Jenis'),
                                                        TableColumn::make('Produk'),
                                                        TableColumn::make('Deskripsi'),
                                                        TableColumn::make('Jumlah'),
                                                        TableColumn::make('Harga Satuan'),
                                                        TableColumn::make('Total Harga'),
                                                    ])
                                                    ->schema([
                                                        TextEntry::make('item_type')
                                                            ->badge()
                                                            ->label('Jenis')
                                                            ->formatStateUsing(fn(string $state) => match ($state) {
                                                                'part'  => 'Part / Barang',
                                                                'labor' => 'Jasa',
                                                                default => $state,
                                                            })
                                                            ->color(fn(string $state) => match ($state) {
                                                                'part'  => 'info',
                                                                'labor' => 'primary',
                                                                default => 'gray',
                                                            }),

                                                        TextEntry::make('product.name')
                                                            ->label('Produk')
                                                            ->placeholder(fn($record) => $record?->description ?: '-'),

                                                        TextEntry::make('description')
                                                            ->label('Deskripsi')
                                                            ->placeholder('-'),

                                                        TextEntry::make('quantity')
                                                            ->label('Qty'),

                                                        TextEntry::make('unit_price')
                                                            ->label('Harga Satuan')
                                                            ->money('IDR', true),

                                                        TextEntry::make('line_total')
                                                            ->label('Sub Total')
                                                            ->money('IDR', true)
                                                            ->weight('bold'),

                                                    ]),

                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
