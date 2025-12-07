<?php

namespace App\Filament\Resources\StockAdjustments\Schemas;

use App\Models\Store;
use App\Models\ProductStock;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ModalTableSelect;
use App\Filament\Tables\ProductStockServiceTable;
use Filament\Forms\Components\Repeater\TableColumn;

class StockAdjustmentForm
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
                            ->columnSpan([
                                'xs' => 12,
                                'sm' => 12,
                                'md' => 4,
                                'lg' => 4,
                            ])
                            ->schema([
                                Section::make('Informasi')
                                    ->columnSpanFull()
                                    ->schema([
                                        Select::make('store_id')
                                            ->label('Bengkel')
                                            ->options(
                                                Store::query()
                                                    ->where('id', Auth::user()->store_id)
                                                    ->pluck('name', 'id')
                                            )

                                            ->default(Auth::user()->store_id)
                                            ->columnSpanFull(),
                                        TextInput::make('reference_number')
                                            ->default(null)
                                            ->disabled(),
                                        DateTimePicker::make('occurred_at')
                                            ->required(),
                                        Textarea::make('note')
                                            ->default(null)
                                            ->columnSpanFull(),
                                    ])
                            ]),

                        Grid::make()
                            ->columnSpan([
                                'xs' => 12,
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([

                                Repeater::make('items')
                                    ->relationship('items')
                                    ->columns(12)
                                    ->columnSpanFull()
                                    ->hiddenLabel()
                                    ->table([
                                        TableColumn::make('Produk'),
                                        TableColumn::make('Jumlah'),
                                        TableColumn::make('Tipe'),
                                        TableColumn::make('Catatan'),
                                    ])
                                    ->schema([
                                        ModalTableSelect::make('product_id')
                                            ->label('Produk')
                                            ->relationship('product', 'name')
                                            ->tableConfiguration(ProductStockServiceTable::class)
                                            ->live()
                                            ->required()
                                            ->distinct(),

                                        TextInput::make('quantity')
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(1)
                                            ->required(),

                                        Select::make('adjustment_type')
                                            ->label('Tipe')
                                            ->options([
                                                'in' => 'Masuk',
                                                'out' => 'Keluar',
                                            ])
                                            ->required(),

                                        TextInput::make('note')
                                            ->default(null)
                                            ->columnSpanFull(),
                                    ])

                            ]),

                    ]),




            ]);
    }
}
