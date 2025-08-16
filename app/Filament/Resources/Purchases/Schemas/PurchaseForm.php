<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Supplier;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater\TableColumn;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([

                        Grid::make()
                            ->columns(8)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                                'xl' => 8,
                            ])
                            ->schema([
                                Section::make('Details')
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 8,
                                        'lg' => 8,
                                    ]) // <== lebar 8/12
                                    ->schema([
                                        TextInput::make('invoice_number')
                                            ->label('Nomor Invoice/Nota Supplier')
                                            ->default(null)
                                            ->columnSpanFull(),
                                        DatePicker::make('purchase_date')
                                            ->label('Tanggal Pembelian (dokumen)')
                                            ->default(now())
                                            ->required()
                                            ->columnSpanFull(),
                                        Select::make('discount_type')
                                            ->label('Diskon')
                                            ->options(['percent' => 'Persen', 'amount' => 'Nominal'])
                                            ->default(null)
                                            ->columnSpanFull(),
                                        TextInput::make('discount_value')
                                            ->label('Nilai Diskon')
                                            ->numeric()
                                            ->default(null)
                                            ->columnSpanFull(),
                                        TextInput::make('price')
                                            ->label('Total Harga')
                                            ->required()
                                            ->numeric()
                                            ->default(0.0)
                                            ->prefix('$')
                                            ->columnSpanFull(),
                                        Textarea::make('notes')
                                            ->label('Catatan')
                                            ->default(null)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Grid::make()
                            ->columns(4)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 4,
                                'lg' => 4,
                                'xl' => 4,
                            ])
                            ->schema([
                                Section::make('Details')
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 4,
                                        'lg' => 4,
                                    ]) // <== lebar 8/12
                                    ->schema([
                                        Select::make('store_id')
                                            ->label('Bengkel')
                                            ->relationship('store', 'name')
                                            ->options(
                                                Store::all()
                                                    ->when(Auth::user()->store_id != null, fn($query) => $query->where('id', Auth::user()->store_id))
                                                    ->pluck('name', 'id')
                                            )
                                            ->disabled(Auth::user()->store_id != null)
                                            ->required(),
                                        Select::make('supplier_id')
                                            ->label('Supplier')
                                            ->relationship('supplier', 'name')
                                            ->options(
                                                Supplier::all()->pluck('name', 'id')
                                            )
                                            ->required(),
                                        Select::make('received_by')
                                            ->label('Diterima Oleh')
                                            ->relationship('receivedBy', 'name')
                                            ->options(
                                                User::all()
                                                    ->when(Auth::user()->store_id != null, fn($query) => $query->where('id', Auth::user()->id))
                                                    ->pluck('name', 'id')
                                            )
                                            ->searchable()
                                            ->disabled(Auth::user()->store_id != null),
                                    ]),
                            ]),
                    ]),

                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->columns(12)
                            ->columnSpanFull()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->columnSpanFull()
                                    ->options(
                                        Product::all()->pluck('name', 'id'),
                                    )
                                    ->searchable()
                                    ->required(),
                                Select::make('price_type')
                                    ->label('Tipe Harga')
                                    ->options([
                                        'toko' => 'Toko',
                                        'distributor' => 'Distributor',
                                    ])
                                    ->columnSpan(4),
                                TextInput::make('quantity_ordered')
                                    ->label('Jumlah Pesanan')
                                    ->required()
                                    ->numeric()
                                    ->columnSpan(4),
                                TextInput::make('unit_purchase_price')
                                    ->label('Harga Beli')
                                    ->required()
                                    ->numeric()
                                    ->columnSpan(4),
                                Select::make('item_discount_type')
                                    ->label('Diskon')
                                    ->options([
                                        'percent' => 'Persen',
                                        'amount' => 'Nominal',
                                    ])
                                    ->columnSpan(6),
                                TextInput::make('item_discount_value')
                                    ->label('Nilai Diskon')
                                    ->numeric()
                                    ->columnSpan(6),
                            ])
                    ])
            ]);
    }
}
