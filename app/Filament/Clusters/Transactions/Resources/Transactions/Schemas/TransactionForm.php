<?php

namespace App\Filament\Clusters\Transactions\Resources\Transactions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                // ==============================
                // KOLOM KIRI: HEADER + ITEMS
                // ==============================
                Group::make()
                    ->columnSpan(8)
                    ->schema([
                        // -------- Header Transaksi --------
                        Section::make('Informasi Transaksi')
                            ->description('Edit informasi utama transaksi & kasir.')
                            ->columns(12)
                            ->schema([
                                TextInput::make('number')
                                    ->label('Nomor Transaksi')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan(4),

                                Select::make('store_id')
                                    ->label('Bengkel')
                                    ->relationship('store', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(4),

                                Select::make('user_id')
                                    ->label('Kasir')
                                    ->relationship('cashier', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(4),

                                Select::make('customer_id')
                                    ->label('Pelanggan')
                                    ->relationship('customer', 'name') // sesuaikan relasi di model
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(4),

                                Select::make('payment_id')
                                    ->label('Metode Pembayaran')
                                    ->relationship('payment', 'name') // sesuaikan
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(4),

                                DateTimePicker::make('transaction_date')
                                    ->label('Tanggal & Jam Transaksi')
                                    ->seconds(false)
                                    ->native(false)
                                    ->displayFormat('d M Y H:i')
                                    ->required()
                                    ->columnSpan(4),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft'     => 'Draft',
                                        'completed' => 'Completed',
                                        'void'      => 'Void',
                                    ])
                                    ->required()
                                    ->columnSpan(3),

                                Select::make('payment_status')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        'unpaid'   => 'Belum Lunas',
                                        'partial'  => 'Cicilan',
                                        'paid'     => 'Lunas',
                                        'refunded' => 'Refund',
                                    ])
                                    ->required()
                                    ->columnSpan(3),

                                Textarea::make('note')
                                    ->label('Catatan')
                                    ->rows(2)
                                    ->columnSpan(12),
                            ]),

                        // -------- Detail Item Transaksi --------
                        Section::make('Item Transaksi')
                            ->description('Perbaiki barang, qty, dan diskon item jika terjadi salah input.')
                            ->collapsible()
                            ->columns(12)
                            ->columnSpanFull()
                            ->schema([
                                Repeater::make('items')
                                    ->label('Daftar Item')
                                    ->relationship('items') // SESUAIKAN dengan relasi di Transaction model
                                    ->defaultItems(0)
                                    ->addActionLabel('Tambah Item')
                                    ->reorderable()
                                    ->columnSpanFull()
                                    ->schema([
                                        Grid::make(12)
                                            ->columnSpanFull()
                                            ->schema([

                                                Select::make('product_id')
                                                    ->label('Produk')
                                                    ->relationship('product', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->columnSpan(4),

                                                TextInput::make('quantity')
                                                    ->label('Qty')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->required()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $qty        = (int) ($get('quantity') ?? 0);
                                                        $unitPrice  = (float) ($get('unit_price') ?? 0);
                                                        $discAmount = (float) ($get('item_discount_amount') ?? 0);

                                                        $lineSubtotal = $qty * $unitPrice;
                                                        $finalUnit    = $qty > 0
                                                            ? ($lineSubtotal - $discAmount) / $qty
                                                            : $unitPrice;
                                                        $lineTotal    = $qty * $finalUnit;

                                                        $set('line_subtotal', $lineSubtotal);
                                                        $set('final_unit_price', $finalUnit);
                                                        $set('line_total', $lineTotal);

                                                        // cost & profit kalau mau dihitung di form juga:
                                                        $unitCost   = (float) ($get('unit_cost') ?? 0);
                                                        $lineCost   = $qty * $unitCost;
                                                        $set('line_cost_total', $lineCost);
                                                        $set('line_profit', $lineTotal - $lineCost);
                                                    })
                                                    ->columnSpan(1),

                                                TextInput::make('unit_price')
                                                    ->label('Harga / Unit')
                                                    ->prefix('Rp')
                                                    ->numeric()
                                                    ->required()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $qty        = (int) ($get('quantity') ?? 0);
                                                        $unitPrice  = (float) ($get('unit_price') ?? 0);
                                                        $discAmount = (float) ($get('item_discount_amount') ?? 0);

                                                        $lineSubtotal = $qty * $unitPrice;
                                                        $finalUnit    = $qty > 0
                                                            ? ($lineSubtotal - $discAmount) / $qty
                                                            : $unitPrice;
                                                        $lineTotal    = $qty * $finalUnit;

                                                        $set('line_subtotal', $lineSubtotal);
                                                        $set('final_unit_price', $finalUnit);
                                                        $set('line_total', $lineTotal);

                                                        $unitCost   = (float) ($get('unit_cost') ?? 0);
                                                        $lineCost   = $qty * $unitCost;
                                                        $set('line_cost_total', $lineCost);
                                                        $set('line_profit', $lineTotal - $lineCost);
                                                    })
                                                    ->columnSpan(3),

                                                Select::make('item_discount_mode')
                                                    ->label('Mode Diskon')
                                                    ->options([
                                                        'percent' => 'Persen (%)',
                                                        'amount'  => 'Nominal (Rp)',
                                                    ])
                                                    ->live()
                                                    ->columnSpan(2),

                                                TextInput::make('item_discount_value')
                                                    ->label('Nilai Diskon')
                                                    ->numeric()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $qty       = (int) ($get('quantity') ?? 0);
                                                        $mode      = $get('item_discount_mode');
                                                        $value     = (float) ($get('item_discount_value') ?? 0);
                                                        $unitPrice = (float) ($get('unit_price') ?? 0);

                                                        $lineSubtotal = $qty * $unitPrice;

                                                        if (! $mode || $value <= 0) {
                                                            $discAmount = 0;
                                                        } elseif ($mode === 'percent') {
                                                            $discAmount = $lineSubtotal * ($value / 100);
                                                        } else {
                                                            $discAmount = $value;
                                                        }

                                                        $finalUnit = $qty > 0
                                                            ? ($lineSubtotal - $discAmount) / $qty
                                                            : $unitPrice;
                                                        $lineTotal = $qty * $finalUnit;

                                                        $set('item_discount_amount', $discAmount);
                                                        $set('line_subtotal', $lineSubtotal);
                                                        $set('final_unit_price', $finalUnit);
                                                        $set('line_total', $lineTotal);

                                                        $unitCost   = (float) ($get('unit_cost') ?? 0);
                                                        $lineCost   = $qty * $unitCost;
                                                        $set('line_cost_total', $lineCost);
                                                        $set('line_profit', $lineTotal - $lineCost);
                                                    })
                                                    ->columnSpan(2),
                                            ]),

                                        Grid::make(12)

                                            ->schema([
                                                TextInput::make('final_unit_price')
                                                    ->label('Harga Akhir / Unit')
                                                    ->prefix('Rp')
                                                    ->numeric()
                                                    ->readOnly()
                                                    ->dehydrated()
                                                    ->columnSpan(3),

                                                TextInput::make('line_subtotal')
                                                    ->label('Subtotal (Sebelum Diskon)')
                                                    ->prefix('Rp')
                                                    ->numeric()
                                                    ->readOnly()
                                                    ->dehydrated()
                                                    ->columnSpan(3),

                                                TextInput::make('line_total')
                                                    ->label('Total (Setelah Diskon)')
                                                    ->prefix('Rp')
                                                    ->numeric()
                                                    ->readOnly()
                                                    ->dehydrated()
                                                    ->columnSpan(3),

                                                TextInput::make('unit_cost')
                                                    ->label('Modal / Unit')
                                                    ->prefix('Rp')
                                                    ->numeric()
                                                    ->helperText('Modal saat transaksi. Ubah jika mau koreksi cost.')
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $qty      = (int) ($get('quantity') ?? 0);
                                                        $unitCost = (float) ($get('unit_cost') ?? 0);
                                                        $lineCost = $qty * $unitCost;
                                                        $lineTotal = (float) ($get('line_total') ?? 0);

                                                        $set('line_cost_total', $lineCost);
                                                        $set('line_profit', $lineTotal - $lineCost);
                                                    })
                                                    ->columnSpan(3),
                                            ]),

                                        Grid::make(12)->schema([
                                            TextInput::make('line_cost_total')
                                                ->label('Total Modal Line')
                                                ->prefix('Rp')
                                                ->numeric()
                                                ->readOnly()
                                                ->dehydrated()
                                                ->columnSpan(4),

                                            TextInput::make('line_profit')
                                                ->label('Laba Kotor Line')
                                                ->prefix('Rp')
                                                ->numeric()
                                                ->readOnly()
                                                ->dehydrated()
                                                ->columnSpan(4),

                                            Select::make('pricing_mode')
                                                ->label('Mode Harga')
                                                ->options([
                                                    'fixed'    => 'Harga Tetap',
                                                    'editable' => 'Boleh Ubah',
                                                ])
                                                ->columnSpan(2),

                                            // Flag kalau harga pernah diubah manual
                                            TextInput::make('price_edited')
                                                ->label('Edited?')
                                                ->hidden() // kalau mau disembunyikan
                                                ->dehydrated()
                                                ->default(false)
                                                ->columnSpan(2),
                                        ]),
                                    ]),
                            ]),
                    ]),

                // ==============================
                // KOLOM KANAN: RINGKASAN
                // ==============================
                Group::make()
                    ->columnSpan(4)
                    ->schema([
                        Section::make('Ringkasan Perhitungan')
                            ->description('Ringkasan subtotal, diskon, pajak, dan grand total.')
                            ->columns(12)
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->helperText('Jumlah sebelum diskon item & diskon universal.')
                                    ->columnSpan(6),

                                TextInput::make('item_discount_total')
                                    ->label('Total Diskon Item')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(6),

                                TextInput::make('subtotal_after_item_discount')
                                    ->label('Subtotal Setelah Diskon Item')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(12),

                                Select::make('universal_discount_mode')
                                    ->label('Diskon Universal')
                                    ->options([
                                        'percent' => 'Persen (%)',
                                        'amount'  => 'Nominal (Rp)',
                                    ])
                                    ->live()
                                    ->columnSpan(4),

                                TextInput::make('universal_discount_value')
                                    ->label('Nilai Diskon Universal')
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $mode   = $get('universal_discount_mode');
                                        $value  = (float) ($get('universal_discount_value') ?? 0);
                                        $sub    = (float) ($get('subtotal_after_item_discount') ?? 0);

                                        if (! $mode || $value <= 0) {
                                            $discAmount = 0;
                                        } elseif ($mode === 'percent') {
                                            $discAmount = $sub * ($value / 100);
                                        } else {
                                            $discAmount = $value;
                                        }

                                        $grand = max($sub - $discAmount, 0);

                                        $set('universal_discount_amount', $discAmount);
                                        $set('grand_total', $grand);
                                    })
                                    ->columnSpan(4),

                                TextInput::make('universal_discount_amount')
                                    ->label('Potongan Universal')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(4),

                                TextInput::make('tax_total')
                                    ->label('Total Pajak')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $subAfterDisc = (float) ($get('subtotal_after_item_discount') ?? 0);
                                        $discUniv     = (float) ($get('universal_discount_amount') ?? 0);
                                        $tax          = (float) ($get('tax_total') ?? 0);

                                        $grand = max($subAfterDisc - $discUniv + $tax, 0);
                                        $set('grand_total', $grand);
                                    })
                                    ->columnSpan(6),

                                TextInput::make('grand_total')
                                    ->label('Grand Total')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(6),

                                TextInput::make('paid_amount')
                                    ->label('Dibayar')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $paid  = (float) ($get('paid_amount') ?? 0);
                                        $grand = (float) ($get('grand_total') ?? 0);
                                        $change = max($paid - $grand, 0);
                                        $set('change_amount', $change);
                                    })
                                    ->columnSpan(6),

                                TextInput::make('change_amount')
                                    ->label('Kembalian')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(6),
                            ]),

                        Section::make('Cost & Profit Keseluruhan')
                            ->description('Ringkasan modal dan laba kotor dari seluruh item.')
                            ->columns(12)
                            ->schema([
                                TextInput::make('total_cost')
                                    ->label('Total Modal')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(6),

                                TextInput::make('total_profit')
                                    ->label('Total Laba Kotor')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(6),
                            ]),
                    ]),
            ]);
    }
}
