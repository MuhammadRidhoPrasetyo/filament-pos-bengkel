<?php

namespace App\Filament\Clusters\Transactions\Resources\Transactions\Tables;

use App\Models\Payment;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Support\Icons\Heroicon;

class TransactionsTable
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
                    ->label('Jumlah Transaksi')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->alignRight()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Jumlah'))
                    ->toggleable(),

                // Dibayar
                TextColumn::make('paid_amount')
                    ->label('Dibayar')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->alignRight()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Kembalian
                TextColumn::make('change_amount')
                    ->label('Kembalian')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->alignRight()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Profit
                TextColumn::make('total_profit')
                    ->label('Laba')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->alignRight()
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Laba')),
                    // ->toggleable(isToggledHiddenByDefault: true),

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
                Filter::make('from')
                    ->schema([
                        DatePicker::make('from')->label('Dari'),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            );
                    }),

                Filter::make('to')
                    ->schema([
                        DatePicker::make('to')->label('Sampai'),
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query

                            ->when(
                                $data['to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    })

            ],  layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)


            ->recordActions([
                Action::make('tambahPembayaran')
                    ->label('Tambah Pembayaran')
                    ->hidden(fn ($record) => in_array($record->payment_status, ['paid', 'refunded']) )
                    ->icon(LucideIcon::DollarSign)
                    ->schema([
                        // show outstanding first (read-only)
                        TextInput::make('outstanding')
                            ->label('Sisa Bayar (Rp)')
                            ->default(fn ($record) => (float) $record->outstanding)
                            ->disabled()
                            ->numeric(),

                        // payment method
                        Select::make('payment_id')
                            ->label('Metode Pembayaran')
                            ->options(Payment::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable(),

                        TextInput::make('amount')
                            ->label('Jumlah (Rp)')
                            ->numeric()
                            ->minValue(0.01)
                            ->required()
                            ->reactive()
                            ->helperText(fn (callable $get, $record) =>
                                'Sisa setelah pembayaran: Rp ' . number_format(max(0, (float) $record->outstanding - (float) ($get('amount') ?? 0)), 0, ',', '.')
                                . ' — Kembalian: Rp ' . number_format(max(0, (float) ($get('amount') ?? 0) - (float) $record->outstanding), 0, ',', '.')
                            ),

                        Textarea::make('note')
                            ->label('Catatan')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Opsional'),
                    ])
                    ->requiresConfirmation()
                    ->action(function ($record, array $data) {
                        $userId = Auth::user()->id;

                        $amount = (float) ($data['amount'] ?? 0);
                        if ($amount <= 0) {
                            throw new \Exception('Jumlah harus lebih besar dari 0');
                        }


                        // apply payment attempt atomically using model helper
                        // we pass the given amount (tendered). The model will compute applied amount and change.
                        $attempt = $record->applyPaymentAttempt($amount, $data['payment_id'] ?? null, $userId);

                        if (! $attempt) {
                            throw new \Exception('Gagal menyimpan pembayaran.');
                        }
                    }),
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
