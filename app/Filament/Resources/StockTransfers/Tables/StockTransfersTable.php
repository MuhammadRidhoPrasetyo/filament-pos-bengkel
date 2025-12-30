<?php

namespace App\Filament\Resources\StockTransfers\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StockTransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->hidden()->searchable(),
                TextColumn::make('fromStore.name')->label('Dari')->searchable(),
                TextColumn::make('toStore.name')->label('Ke')->searchable(),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('reference_number')->label('Ref')->searchable(),
                TextColumn::make('occurred_at')->label('Tanggal')->dateTime()->sortable(),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
