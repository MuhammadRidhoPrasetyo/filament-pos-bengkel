<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('No. Struk')
                    ->badge()
                    ->weight('semibold')
                    ->sortable()
                    ->searchable(),

                // Tanggal Transaksi
                TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                // Toko
                TextColumn::make('store.name')
                    ->label('Toko')
                    ->icon('heroicon-o-building-storefront')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                // Kasir
                TextColumn::make('cashier.name')
                    ->label('Kasir')
                    ->icon('heroicon-o-user')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                // Customer
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->placeholder('Walk-in / Umum')
                    ->icon('heroicon-o-user-group')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                // Metode Pembayaran
                TextColumn::make('payment.name')
                    ->label('Metode')
                    ->icon('heroicon-o-credit-card')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(),

                // Grand Total
                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->money('IDR', locale: 'id')
                    ->alignRight()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total'))
                    ->toggleable(),

                // Dibayar
                TextColumn::make('paid_amount')
                    ->label('Dibayar')
                    ->money('IDR', locale: 'id')
                    ->alignRight()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Kembalian
                TextColumn::make('change_amount')
                    ->label('Kembalian')
                    ->money('IDR', locale: 'id')
                    ->alignRight()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Profit
                TextColumn::make('total_profit')
                    ->label('Laba Kotor')
                    ->money('IDR', locale: 'id')
                    ->alignRight()
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Laba'))
                    ->toggleable(isToggledHiddenByDefault: true),

                // Status Pembayaran
                TextColumn::make('payment_status')
                    ->badge()
                    ->label('Status Bayar')
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'partial',
                        'danger'  => 'unpaid',
                        'gray'    => 'refunded',
                    ])
                    ->sortable(),

                // Status Transaksi
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'draft',
                        'danger'  => 'void',
                    ])
                    ->sortable(),
            ])
            ->defaultSort('transaction_date', 'desc')
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
