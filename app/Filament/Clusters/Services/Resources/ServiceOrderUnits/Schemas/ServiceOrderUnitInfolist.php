<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;

class ServiceOrderUnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('id')
                //     ->label('ID'),
                // TextEntry::make('service_order_id'),
                // TextEntry::make('customer_vehicle_id')
                //     ->placeholder('-'),
                // TextEntry::make('status')
                //     ->badge(),
                // TextEntry::make('checkin_at')
                //     ->dateTime(),
                // TextEntry::make('completed_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('complaint')
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('diagnosis')
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('work_done')
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('estimated_total')
                //     ->numeric(),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('plate_number'),
                // TextEntry::make('brand')
                //     ->placeholder('-'),
                // TextEntry::make('model')
                //     ->placeholder('-'),
                // TextEntry::make('year')
                //     ->placeholder('-'),
                // TextEntry::make('color')
                //     ->placeholder('-'),

                Section::make('Unit yang Diservis')
                    ->description('Setiap unit mewakili satu motor / kendaraan.')
                    ->columnSpanFull()
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

                                Section::make('Part & Jasa')
                                    ->description('Spare Part & Jasa yang digunakan pada unit ini.')
                                    ->schema([
                                        RepeatableEntry::make('items')
                                            ->hiddenLabel()
                                            ->table([
                                                TableColumn::make('Produk'),
                                                TableColumn::make('Deskripsi'),
                                                TableColumn::make('Qty'),
                                                TableColumn::make('Harga Satuan'),
                                                TableColumn::make('Total'),
                                            ])
                                            ->schema([
                                                TextEntry::make('product_label')
                                                    ->label('Produk')
                                                    ->getStateUsing(fn($record) => $record?->product?->label ?? $record?->product?->name)
                                                    ->placeholder('-'),
                                                TextEntry::make('description'),
                                                TextEntry::make('quantity'),
                                                TextEntry::make('unit_price'),
                                                TextEntry::make('line_total'),
                                            ])
                                    ]),
                            ]),

                    ]),

            ]);
    }
}
