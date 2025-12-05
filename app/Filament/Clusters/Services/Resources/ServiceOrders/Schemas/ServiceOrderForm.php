<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Schemas;

use App\Filament\Tables\ProductStockServiceTable;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\ServiceOrder;
use Filament\Schemas\Schema;
use App\Models\ServiceOrderUnit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\ModalTableSelect;

class ServiceOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // =======================
                //  INFORMASI UMUM
                // =======================
                Section::make('Informasi Umum')
                    ->columns(12)
                    ->schema([
                        Select::make('store_id')
                            ->label('Toko')
                            ->options(fn() => Store::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray())
                            ->searchable()
                            ->required()
                            ->columnSpan(6),

                        Select::make('customer_id')
                            ->label('Pelanggan')
                            ->options(fn() => Supplier::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray())
                            ->searchable()
                            ->required()
                            ->columnSpan(6),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'checkin' => 'Masuk',
                                'in_progress' => 'Dalam Proses',
                                'waiting_parts' => 'Menunggu Sparepart',
                            ])
                            ->columnSpanFull(),

                        Textarea::make('general_complaint')
                            ->label('Keluhan Umum')
                            ->rows(2)
                            ->columnSpanFull(),

                        DateTimePicker::make('checkin_at')
                            ->label('Waktu Masuk')
                            ->required()
                            ->columnSpan(4),

                        DateTimePicker::make('completed_at')
                            ->label('Perkiraan Waktu Selesai')
                            ->columnSpan(4),

                        TextInput::make('estimated_total')
                            ->label('Estimasi Total')
                            ->numeric()
                            ->columnSpan(4)
                            ->readOnly(),
                    ]),

                // =======================
                //  UNIT SERVIS
                // =======================
                Section::make('Unit Servis')
                    ->schema([
                        Repeater::make('units')
                            ->label('Unit Motor')
                            // relasi ke model: ServiceOrder::units()
                            ->relationship('units')
                            ->defaultItems(1)
                            ->minItems(1)
                            ->columns(1)
                            ->itemLabel(fn(array $state): ?string => $state['plate_number'] ?? null)
                            ->schema([
                                Select::make('mechanics')
                                    ->label('Mekanik')
                                    ->relationship('mechanics', 'name')
                                    ->options(fn() => User::query()
                                        ->whereHas('roles', fn($q) => $q->where('name', 'mechanic'))
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->toArray())
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),

                                Grid::make(3)
                                    ->schema([
                                        DateTimePicker::make('checkin_at')
                                            ->label('Waktu Masuk')
                                            ->required(),

                                        DateTimePicker::make('completed_at')
                                            ->label('Waktu Selesai')
                                            ->required(),

                                        Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'checkin' => 'Masuk',
                                                'in_progress' => 'Dalam Proses',
                                                'waiting_parts' => 'Menunggu Sparepart',
                                            ]),
                                    ]),

                                // Data kendaraan
                                Grid::make(4)
                                    ->schema([
                                        TextInput::make('plate_number')
                                            ->label('Nomor Polisi')
                                            ->required(),

                                        TextInput::make('brand')
                                            ->label('Merek'),

                                        TextInput::make('model')
                                            ->label('Model'),

                                        TextInput::make('color')
                                            ->label('Warna'),
                                    ]),

                                Textarea::make('complaint')
                                    ->label('Keluhan')
                                    ->rows(2),

                                Textarea::make('diagnosis')
                                    ->label('Diagnosis')
                                    ->rows(2),

                                Textarea::make('work_done')
                                    ->label('Pekerjaan Dilakukan')
                                    ->rows(2),

                                Section::make()
                                    ->schema([
                                        Repeater::make('items')
                                            ->label('Spare Part / Jasa')
                                            ->relationship('items')
                                            ->defaultItems(0)
                                            ->minItems(0)
                                            ->columns(12)
                                            ->schema([
                                                ModalTableSelect::make('product_id')
                                                    ->relationship('product', 'name')
                                                    ->tableConfiguration(ProductStockServiceTable::class)
                                                    ->live()
                                                    ->distinct()
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        // Kalau produk dihapus / dikosongkan
                                                        if (! $state) {
                                                            $set('qty', null);
                                                            $set('unit_price', null);
                                                            $set('line_total', null);

                                                            return;
                                                        }

                                                        // Ambil produk beserta relasi yang dibutuhkan
                                                        $product = Product::query()
                                                            ->with(['stock.productPrice'])
                                                            ->find($state);

                                                        if (! $product) {
                                                            return;
                                                        }

                                                        // --- Tentukan qty default ---
                                                        // Jika kamu mau qty = 1 (lebih masuk akal untuk service order)
                                                        $qty = 1;

                                                        // Atau kalau mau qty awal = stok yang tersedia (hati-hati, ini stok, bukan qty yang dipakai):
                                                        // $qty = $product->stock->quantity ?? 1;

                                                        // --- Ambil harga jual dari relasi productPrice ---
                                                        $unitPrice = optional(optional($product->stock)->productPrice)->selling_price ?? 0;

                                                        $lineTotal = $qty * $unitPrice;

                                                        // Set ke field lain di repeater item yang sama
                                                        $set('qty', $qty);
                                                        $set('unit_price', $unitPrice);
                                                        $set('line_total', $lineTotal);
                                                    })
                                                    ->columnSpan(3),

                                                TextInput::make('description')
                                                    ->label('Deskripsi')
                                                    ->columnSpan(3),

                                                TextInput::make('quantity')
                                                    ->label('Qty')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->maxValue(function (Get $get) {
                                                        $productId = $get('product_id');

                                                        if (! $productId) {
                                                            return null; // tidak ada batasan sebelum user pilih produk
                                                        }

                                                        $product = \App\Models\Product::query()
                                                            ->with('stock')
                                                            ->find($productId);

                                                        return $product?->stock?->quantity ?? null;
                                                    })
                                                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                                        // hitung line_total item ini dulu
                                                        $qty   = (float) $state;
                                                        $price = (float) $get('unit_price');
                                                        $lineTotal = $qty * $price;

                                                        $set('line_total', $lineTotal);

                                                        // ambil semua units dari root form
                                                        $units = $get('../../../../units') ?? [];

                                                        $grandTotal = 0;

                                                        foreach ($units as $unit) {
                                                            foreach ($unit['items'] ?? [] as $item) {
                                                                $grandTotal += (float) ($item['line_total'] ?? 0);
                                                            }
                                                        }

                                                        // set ke field header estimated_total di root
                                                        $set('../../../../estimated_total', $grandTotal);
                                                    })
                                                    ->columnSpan(1),

                                                TextInput::make('unit_price')
                                                    ->label('Harga')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->required()
                                                    ->readOnly()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                                        $qty   = (float) $get('qty');
                                                        $price = (float) $state;

                                                        $set('line_total', $qty * $price);
                                                    })
                                                    ->columnSpan(2),

                                                TextInput::make('line_total')
                                                    ->label('Subtotal')
                                                    ->numeric()
                                                    ->live(onBlur: true)
                                                    ->readonly()
                                                    ->columnSpan(3),
                                            ])
                                            ->columnSpanFull()
                                            ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                                                dd($data);
                                                $data['estimated_total'] = array_sum(array_column($data['items'], 'line_total'));

                                                return $data;
                                            }),
                                    ]),


                            ]),
                    ]),
            ]);
    }
}
