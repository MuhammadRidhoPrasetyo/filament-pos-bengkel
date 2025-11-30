<?php

namespace App\Filament\Clusters\Transactions\Resources\Transactions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

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
                                            ->label('Toko')
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
                                Grid::make(3)->schema([
                                    TextEntry::make('subtotal')
                                        ->label('Subtotal')
                                        ->money('IDR', locale: 'id')
                                        ->weight('medium'),

                                    TextEntry::make('item_discount_total')
                                        ->label('Diskon Item')
                                        ->money('IDR', locale: 'id')
                                        ->color('danger'),

                                    TextEntry::make('subtotal_after_item_discount')
                                        ->label('Setelah Diskon Item')
                                        ->money('IDR', locale: 'id')
                                        ->weight('medium'),
                                ]),

                                Grid::make(3)->schema([
                                    TextEntry::make('universal_discount_amount')
                                        ->label('Diskon Universal')
                                        ->money('IDR', locale: 'id')
                                        ->color('danger'),

                                    TextEntry::make('tax_total')
                                        ->label('Pajak')
                                        ->money('IDR', locale: 'id')
                                        ->color('warning')
                                        ->hidden(),

                                    TextEntry::make('grand_total')
                                        ->label('Grand Total')
                                        ->money('IDR', locale: 'id')
                                        ->weight('bold'),
                                ]),

                                Grid::make(3)->schema([
                                    TextEntry::make('paid_amount')
                                        ->label('Dibayar')
                                        ->money('IDR', locale: 'id')
                                        ->color('success'),

                                    TextEntry::make('change_amount')
                                        ->label('Kembalian')
                                        ->money('IDR', locale: 'id')
                                        ->color('success'),

                                    TextEntry::make('total_profit')
                                        ->label('Laba Kotor')
                                        ->money('IDR', locale: 'id')
                                        ->color(fn($state) => $state >= 0 ? 'success' : 'danger'),
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
                                    ->schema([

                                        Grid::make(3)
                                            ->schema([
                                                    TextEntry::make('product.name')
                                            ->label('Produk')
                                            ->weight('medium'),

                                        TextEntry::make('quantity')
                                            ->label('Qty')
                                            ->numeric(),
                                            ]),

                                        Grid::make(7)->schema([
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
                                        ])
                                    ]),
                            ]),

                    ])
                    ->columnSpanFull(),

            ]);
    }
}
