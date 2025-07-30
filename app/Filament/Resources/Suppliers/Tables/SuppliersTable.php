<?php

namespace App\Filament\Resources\Suppliers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('code')
                    ->label('Kode Supplier')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('contact_person')
                    ->label('Kontak Person')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Nomor Handphone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('province')
                    ->label('Provinsi')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('postal_code')
                    ->label('Kode Pos')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('npwp')
                    ->label('NPWP')
                    ->searchable(),
                TextColumn::make('bank_name')
                    ->label('Nama Bank')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('bank_account')
                    ->label('Nomor Rekening')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('created_at')
                    ->hidden()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('updated_at')
                    ->hidden()
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
