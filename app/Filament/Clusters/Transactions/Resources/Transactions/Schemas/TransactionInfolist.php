<?php

namespace App\Filament\Clusters\Transactions\Resources\Transactions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('Tabs')
                    ->contained(false)
                    ->tabs([
                        Tab::make('Detail Transaksi')
                            ->icon(LucideIcon::ReceiptText)
                            ->schema([
                                Grid::make(12)
                                    ->schema([
                                        // =======================
                                        // 1. INFORMASI TRANSAKSI
                                        // =======================
                                        Section::make('Informasi Transaksi')
                                            ->icon('heroicon-o-receipt-percent')
                                            ->description('Detail umum transaksi POS.')
                                            ->columnSpanFull()
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextEntry::make('number')
                                                            ->label('Nomor Struk')
                                                            ->badge()
                                                            ->copyable()
                                                            ->copyMessage('Nomor struk disalin.')
                                                            ->weight('semibold'),

                                                        TextEntry::make('transaction_date')
                                                            ->label('Tanggal')
                                                            ->dateTime('d M Y H:i'),

                                                        TextEntry::make('store.name')
                                                            ->label('Bengkel')
                                                            ->icon('heroicon-o-building-storefront')
                                                            ->weight('medium'),

                                                        TextEntry::make('cashier.name')
                                                            ->label('Kasir')
                                                            ->icon('heroicon-o-user')
                                                            ->color('gray'),

                                                        TextEntry::make('customer.name')
                                                            ->label('Customer')
                                                            ->placeholder('Walk-in / Umum')
                                                            ->icon('heroicon-o-user-group'),

                                                        TextEntry::make('payment.name')
                                                            ->label('Metode Pembayaran')
                                                            ->icon('heroicon-o-credit-card')
                                                            ->placeholder('-'),

                                                        TextEntry::make('status')
                                                            ->label('Status Transaksi')
                                                            ->badge()
                                                            ->formatStateUsing(fn(string $state) => ucfirst($state))
                                                            ->color(fn(string $state) => match ($state) {
                                                                'completed' => 'success',
                                                                'draft'     => 'warning',
                                                                'void'      => 'danger',
                                                                default     => 'gray',
                                                            }),

                                                        TextEntry::make('payment_status')
                                                            ->label('Status Pembayaran')
                                                            ->badge()
                                                            ->formatStateUsing(fn(string $state) => ucfirst($state))
                                                            ->color(fn(string $state) => match ($state) {
                                                                'paid'     => 'success',
                                                                'partial'  => 'warning',
                                                                'unpaid'   => 'danger',
                                                                'refunded' => 'gray',
                                                                default    => 'gray',
                                                            }),
                                                    ]),
                                            ]),

                                        // =======================
                                        // 2. RINGKASAN ANGKA
                                        // =======================
                                        Section::make('Ringkasan Pembayaran')
                                            ->icon('heroicon-o-calculator')
                                            ->description('Rekap nilai transaksi dan profit.')
                                            ->columnSpanFull()
                                            ->schema([
                                                        // Redesigned payment summary with attempts info

                                                            // Top row: Grand total + Paid + Outstanding
                                                            Grid::make(3)->schema([
                                                                TextEntry::make('grand_total')
                                                                    ->label('Grand Total')
                                                                    ->money('IDR', locale: 'id')
                                                                    ->weight('bold')
                                                                    ->icon(LucideIcon::DollarSign),

                                                                TextEntry::make('paid_amount')
                                                                    ->label('Sudah Dibayar')
                                                                    ->money('IDR', locale: 'id')
                                                                    ->color('success')
                                                                    ->icon(LucideIcon::CheckCircle),

                                                                TextEntry::make('outstanding')
                                                                    ->label('Tersisa (Outstanding)')
                                                                    ->money('IDR', locale: 'id')
                                                                    ->color(fn($state, $record) => $record->outstanding > 0 ? 'danger' : 'success')
                                                                    ->formatStateUsing(function ($state, $record) {
                                                                        // Use accessor on model if available
                                                                        return $record->outstanding ?? 0;
                                                                    })
                                                                    ->icon(LucideIcon::Clock),
                                                            ]),

                                                            // Second row: Attempts summary + last payment + attempts total
                                                            Grid::make(3)->schema([
                                                                TextEntry::make('attempts_summary')
                                                                    ->label('Riwayat Pembayaran')
                                                                    ->formatStateUsing(function ($state, $record) {
                                                                        $count = $record->paymentAttempts()->count();
                                                                        $sum = (float) $record->paymentAttempts()->sum('amount');
                                                                        $sumDisplay = 'Rp ' . number_format($sum, 0, ',', '.');
                                                                        return sprintf('%d upaya • %s diterapkan', $count, $sumDisplay);
                                                                    })
                                                                    ->icon('heroicon-o-clipboard-list'),

                                                                TextEntry::make('last_payment')
                                                                    ->label('Pembayaran Terakhir')
                                                                    ->formatStateUsing(function ($state, $record) {
                                                                        $last = $record->paymentAttempts()->latest('paid_at')->first();
                                                                        return $last ? $last->paid_at->format('d M Y H:i') : '-';
                                                                    })
                                                                    ->icon('heroicon-o-clock'),

                                                                TextEntry::make('attempts_count')
                                                                    ->label('Jumlah Upaya')
                                                                    ->formatStateUsing(function ($state, $record) {
                                                                        return $record->paymentAttempts()->count();
                                                                    })
                                                                    ->icon('heroicon-o-user-group'),
                                                            ]),

                                                            // Third row: amount given total and change total (if available)
                                                            Grid::make(3)->schema([
                                                                TextEntry::make('attempts_amount_given')
                                                                    ->label('Total Diberikan')
                                                                    ->formatStateUsing(function ($state, $record) {
                                                                        $sum = (float) $record->paymentAttempts()->sum('amount_given');
                                                                        return 'Rp ' . number_format($sum, 0, ',', '.');
                                                                    })
                                                                    ->icon('heroicon-o-hand-holding-dollar')
                                                                    ->color('primary'),

                                                                TextEntry::make('attempts_change')
                                                                    ->label('Total Kembalian')
                                                                    ->formatStateUsing(function ($state, $record) {
                                                                        $sum = (float) $record->paymentAttempts()->sum('change');
                                                                        return 'Rp ' . number_format($sum, 0, ',', '.');
                                                                    })
                                                                    ->icon('heroicon-o-arrow-path')
                                                                    ->color('success'),

                                                                TextEntry::make('payment_methods')
                                                                    ->label('Metode yang digunakan')
                                                                    ->formatStateUsing(function ($state, $record) {
                                                                        $methods = $record->paymentAttempts()->with('payment')->get()->pluck('payment.name')->filter()->unique()->values()->toArray();
                                                                        return $methods ? implode(', ', $methods) : '-';
                                                                    })
                                                                    ->icon('heroicon-o-credit-card')
                                                                    ->placeholder('-'),
                                                            ]),

                                            ]),

                                        // =======================
                                        // 3. DETAIL ITEM
                                        // =======================
                                        Section::make('Rincian Item')
                                            ->icon('heroicon-o-queue-list')
                                            ->description('Daftar barang / jasa yang dijual dalam transaksi ini.')
                                            ->collapsed(false)
                                            ->columnSpanFull()
                                            ->schema([
                                                RepeatableEntry::make('items')
                                                    ->label('Item Transaksi')
                                                    ->columnSpanFull()
                                                    ->table([
                                                        TableColumn::make('Produk'),
                                                        TableColumn::make('Jumlah'),
                                                        TableColumn::make('Harga Satuan'),
                                                        TableColumn::make('Diskon'),
                                                        TableColumn::make('Harga Nett'),
                                                        TableColumn::make('Total'),
                                                        TableColumn::make('Profit Line'),
                                                        TableColumn::make('Mode Harga'),
                                                        TableColumn::make('Jenis Diskon'),
                                                    ])
                                                    ->schema([
                                                        TextEntry::make('product.productLabel.display_name')
                                                            ->label('Produk')
                                                            ->weight('medium'),

                                                        TextEntry::make('quantity')
                                                            ->label('Qty')
                                                            ->numeric(),

                                                        TextEntry::make('unit_price')
                                                            ->label('Harga')
                                                            ->money('IDR', locale: 'id'),

                                                        TextEntry::make('item_discount_amount')
                                                            ->label('Diskon')
                                                            ->money('IDR', locale: 'id')

                                                            ->color('danger')
                                                            ->formatStateUsing(function ($state, $record) {
                                                                if (! $state || $state == 0) {
                                                                    return 'Rp 0';
                                                                }

                                                                $mode  = $record->item_discount_mode;
                                                                $value = $record->item_discount_value;

                                                                if ($mode === 'percent') {
                                                                    return sprintf(
                                                                        '-%s (%s%%)',
                                                                        number_format($state, 0, ',', '.'),
                                                                        $value
                                                                    );
                                                                }

                                                                return sprintf(
                                                                    '-%s',
                                                                    number_format($state, 0, ',', '.')
                                                                );
                                                            }),

                                                        TextEntry::make('final_unit_price')
                                                            ->label('Harga Nett')
                                                            ->money('IDR', locale: 'id'),

                                                        TextEntry::make('line_total')
                                                            ->label('Total')
                                                            ->money('IDR', locale: 'id')

                                                            ->weight('semibold')
                                                            ->color('primary'),

                                                        // Baris kedua: info tambahan kecil (di bawah span penuh)
                                                        TextEntry::make('line_profit')
                                                            ->label('Profit Line')
                                                            ->money('IDR', locale: 'id')
                                                            ->color(fn($state) => $state >= 0 ? 'success' : 'danger'),

                                                        TextEntry::make('pricing_mode')
                                                            ->label('Mode Harga')
                                                            ->badge()
                                                            ->color(fn(?string $state) => match ($state) {
                                                                'editable' => 'warning',
                                                                'fixed'    => 'gray',
                                                                default    => 'gray',
                                                            }),

                                                        TextEntry::make('discountType.name')
                                                            ->label('Jenis Diskon')
                                                            ->placeholder('-')
                                                            ->badge()
                                                            ->color('info'),

                                                    ]),
                                            ]),

                                    ])
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Riwayat Pembayaran')
                            ->icon(LucideIcon::CreditCard)
                            ->schema([
                                Section::make('Riwayat Pembayaran')
                                    ->icon(LucideIcon::History)
                                    ->description('Catatan semua upaya pembayaran untuk transaksi ini.')
                                    ->collapsed(false)
                                    ->schema([
                                        RepeatableEntry::make('paymentAttempts')
                                            ->label('Riwayat Pembayaran')
                                            ->columnSpanFull()
                                            ->table([
                                                TableColumn::make('Dibayar Pada'),
                                                TableColumn::make('User'),
                                                TableColumn::make('Metode Pembayaran'),
                                                TableColumn::make('Jumlah Diberikan'),
                                                TableColumn::make('Jumlah Diterapkan'),
                                                TableColumn::make('Kembalian'),
                                                TableColumn::make('Metadata'),
                                            ])
                                            ->schema([
                                                TextEntry::make('paid_at')
                                                    ->label('Dibayar Pada')
                                                    ->dateTime('d M Y H:i'),

                                                TextEntry::make('user.name')
                                                    ->label('User')
                                                    ->placeholder('-'),

                                                TextEntry::make('payment.name')
                                                    ->label('Metode Pembayaran')
                                                    ->placeholder('-'),

                                                TextEntry::make('amount_given')
                                                    ->label('Jumlah Diberikan')
                                                    ->money('IDR', locale: 'id')
                                                    ->color('success'),

                                                TextEntry::make('amount')
                                                    ->label('Jumlah Diterapkan')
                                                    ->money('IDR', locale: 'id'),

                                                TextEntry::make('change')
                                                    ->label('Kembalian')
                                                    ->money('IDR', locale: 'id')
                                                    ->color('success'),

                                                TextEntry::make('metadata')
                                                    ->label('Metadata')
                                                    ->formatStateUsing(function ($state) {
                                                        if (is_array($state)) {
                                                            return json_encode($state, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                                        }

                                                        return $state ?: '-';
                                                    })
                                                    ->placeholder('-'),
                                            ]),
                                    ])


                            ]),

                    ])
                    ->columnSpanFull(),



            ]);
    }
}
