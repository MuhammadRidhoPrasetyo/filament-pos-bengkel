<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class ServiceOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                // fn(Builder $query) => $query
                //     ->when(!Auth::user()->hasRole('owner'), function ($query) {
                //         return $query->where('store_id', Auth::user()->store_id);
                //     })
                fn(Builder $query) => $query
                    ->where('store_id', Auth::user()->store_id)
            )
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
                Filter::make('from')
                    ->schema([
                        DatePicker::make('from')->label('Dari'),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('checkin_at', '>=', $date),
                            );
                    }),

                Filter::make('to')
                    ->schema([
                        DatePicker::make('to')->label('Sampai'),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query

                            ->when(
                                $data['to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('checkin_at', '<=', $date),
                            );
                    })

            ],  layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
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
