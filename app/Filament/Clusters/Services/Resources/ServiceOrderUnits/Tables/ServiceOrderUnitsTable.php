<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Tables\Columns\SelectColumn;

class ServiceOrderUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('serviceOrder.number')
                    ->label('Nomor Servis')
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
                    ]),
                TextColumn::make('plate_number')
                    ->label('Plat Nomor')
                    ->searchable(),
                TextColumn::make('brand')
                    ->label('Merk')
                    ->searchable(),
                TextColumn::make('model')
                    ->label('Model')
                    ->searchable(),
                TextColumn::make('color')
                    ->label('Warna')
                    ->searchable(),
                TextColumn::make('checkin_at')
                    ->label('Jam Masuk')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Jam Selesai')
                    ->dateTime()
                    ->sortable(),
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
