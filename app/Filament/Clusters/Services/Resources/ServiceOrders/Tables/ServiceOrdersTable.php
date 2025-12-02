<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class ServiceOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('number')
                    ->label('Nomor Servis')
                    ->searchable(),
                TextColumn::make('store.name')
                    ->label('Toko')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'checkin' => 'Masuk',
                        'diagnosis' => 'Diagnosis',
                        'in_progress' => 'Dalam Proses',
                        'waiting_parts' => 'Menunggu Sparepart',
                        'ready' => 'Siap',
                        'invoiced' => 'Sudah Dibayar',
                        'cancelled' => 'Batal',
                    ])
                    ->disabled(fn($record) => $record->status === 'invoiced'),
                TextColumn::make('checkin_at')
                    ->label('Jam Masuk')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Jam Selesai')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('transaction.number')
                    ->label('Nomor Transaksi')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('Export'),
                ]),
            ]);
    }
}
