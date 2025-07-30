<?php

namespace App\Filament\Resources\Stores\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->rowIndex()
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Kode Bengkel')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Bengkel')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Nomor Handphone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),
                TextColumn::make('province')
                    ->label('Provinsi')
                    ->searchable(),
                TextColumn::make('postal_code')
                    ->label('Kode Pos')
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
                ]),
            ]);
    }
}
