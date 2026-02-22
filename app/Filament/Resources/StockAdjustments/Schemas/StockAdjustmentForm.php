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
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

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
                                Section::make('Informasi Penyesuaian')
                                    ->icon(LucideIcon::Gauge)
                                    ->description('Catat penyesuaian stok karena inventaris, rusak, atau pembulatan.')
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
                                            ->columnSpanFull()
                                            ->disabled(),
                                        TextInput::make('reference_number')
                                            ->label('Nomor Referensi')
                                            ->default(null)
                                            ->disabled()
                                            ->helperText('Nomor otomatis yang dibuat saat menyimpan.'),
                                        DateTimePicker::make('occurred_at')
                                            ->label('Tanggal & Waktu')
                                            ->required(),
                                        Textarea::make('note')
                                            ->label('Catatan')
                                            ->default(null)
                                            ->columnSpanFull()
                                            ->helperText('Alasan penyesuaian stok (cth: Inventaris fisik, barang rusak).'),
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

                                Section::make('Rincian Penyesuaian')
                                    ->icon(LucideIcon::TrendingUp)
                                    ->description('Tambahkan produk dan jumlah penyesuaian (masuk/keluar) untuk setiap item.')
                                    ->columnSpanFull()
                                    ->schema([
                                        Repeater::make('items')
                                            ->relationship('items')
                                            ->columns(12)
                                            ->columnSpanFull()
                                            ->hiddenLabel()
                                            ->addActionLabel('Tambah Item')
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
                                                        'in' => 'Masuk (Tambah)',
                                                        'out' => 'Keluar (Kurang)',
                                                    ])
                                                    ->required(),

                                                TextInput::make('note')
                                                    ->label('Catatan')
                                                    ->default(null)
                                                    ->columnSpanFull()
                                                    ->helperText('Keterangan item khusus jika diperlukan.'),
                                            ])
                                    ])

                            ]),

                    ]),




            ]);
    }
}
