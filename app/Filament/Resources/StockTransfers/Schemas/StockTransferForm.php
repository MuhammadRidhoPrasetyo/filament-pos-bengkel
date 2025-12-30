<?php

namespace App\Filament\Resources\StockTransfers\Schemas;

use App\Models\Store;
use App\Models\Product;
use App\Models\ProductStock;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ModalTableSelect;
use App\Filament\Tables\ProductStockServiceTable;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Repeater\TableColumn;

class StockTransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make()
                ->columns(12)
                ->columnSpanFull()
                ->schema([
                    Section::make('Informasi')
                        ->icon(LucideIcon::Truck)
                        ->description('Digunakan untuk membuat dokumen transfer stok antar bengkel. Setelah diposting, stok akan berkurang di bengkel asal dan bertambah di bengkel tujuan.')
                        ->schema([
                            Select::make('from_store_id')
                                ->label('Dari Bengkel')
                                ->options(fn() => Store::pluck('name', 'id'))
                                ->default(fn() => auth()->user()->store_id)
                                ->required(),

                            Select::make('to_store_id')
                                ->label('Ke Bengkel')
                                ->options(
                                    fn() => Store::whereNotIn('id', [auth()->user()->store_id])
                                        ->pluck('name', 'id')
                                )
                                ->required(),

                            TextInput::make('reference_number')
                                ->label('Nomor Referensi')
                                ->hidden(CreateAction::class)
                                ->disabled(),

                            DateTimePicker::make('occurred_at')
                                ->label('Tanggal')
                                ->required(),

                            Textarea::make('note')
                                ->label('Catatan')
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),

                    Section::make('Rincian Item')
                        ->icon(LucideIcon::Truck)
                        ->description('Tambahkan produk dan jumlah yang akan dipindahkan. Batas maksimum mengikuti stok di bengkel asal.')
                        ->schema([
                            Repeater::make('items')
                                ->hiddenLabel()
                                ->relationship('items')
                                ->table([
                                    TableColumn::make('Produk'),
                                    TableColumn::make('Jumlah'),
                                ])
                                ->schema([
                                    ModalTableSelect::make('product_id')
                                        ->label('Produk')
                                        ->relationship('product', 'name')
                                        ->tableConfiguration(ProductStockServiceTable::class)
                                        ->getOptionLabelFromRecordUsing(fn(Product $record): string => $record->label)
                                        ->required(),

                                    TextInput::make('quantity')
                                        ->label('Jumlah')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(fn(callable $get) => ProductStock::where('product_id', $get('product_id'))
                                            ->where('store_id', $get('../../from_store_id'))
                                            ->value('quantity') ?? null)
                                        ->required(),
                                ])
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
