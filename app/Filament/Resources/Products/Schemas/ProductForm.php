<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Unit;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\DiscountType;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Filament\Schemas\Schema;
use App\Models\ProductCategory;
use App\Models\ProductDiscount;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class ProductForm
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
                                    ->icon(LucideIcon::Info)
                                    ->description('Informasi dasar produk: beri nama, SKU, dan kata kunci yang mudah ditemukan. Buat deskripsi singkat agar pelanggan cepat mengerti keunggulan produk.')
                                    ->inlineLabel()
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 8,
                                        'lg' => 8,
                                    ]) // <== lebar 8/12
                                    ->schema([
                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->default(null),
                                        TextInput::make('name')
                                            ->label('Nama Produk')
                                            ->required(),
                                        TextInput::make('type')
                                            ->label('Tipe Produk')
                                            ->default(null),
                                        TextInput::make('keyword')
                                            ->label('Kata Kunci')
                                            ->default(null),
                                        TextInput::make('compatibility')
                                            ->placeholder('Cont. Yamaha, Honda, Suzuki, dll')
                                            ->label('Kompatibilitas')
                                            ->default(null),
                                        TextInput::make('size')
                                            ->label('Ukuran')
                                            ->default(null),
                                        Textarea::make('description')
                                            ->label('Keterangan')
                                            ->default(null)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Inisialisasi Stok Barang')
                                    ->icon(LucideIcon::Package)
                                    ->description('Inisialisasi stok per toko agar produk langsung tersedia. Hanya pemilik (owner) yang dapat menambah atau menghapus entri stok.')
                                    ->schema([
                                        Repeater::make('stocks')
                                            ->relationship('stocks')
                                            ->hiddenLabel()
                                            ->visibleOn('create')
                                            ->columns(12)
                                            ->columnSpanFull()
                                            ->addActionLabel('Tambah Stok')
                                            ->addable(fn() => Auth::user()->hasRole('owner'))
                                            ->deletable(fn() => Auth::user()->hasRole('owner'))
                                            ->relationship('stocks') // otomatis isi product_id
                                            ->table([
                                                TableColumn::make('Bengkel'),
                                                TableColumn::make('Tanggal Stok'),
                                                TableColumn::make('Jumlah'),
                                            ])
                                            ->schema([

                                                Select::make('store_id')
                                                    ->label('Toko')
                                                    ->columnSpanFull()
                                                    ->options(Store::query()
                                                        ->when(!Auth::user()->hasRole('owner'), function ($query) {
                                                            return $query->where('id', Auth::user()->store_id);
                                                        })
                                                        ->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->distinct(),

                                                DatePicker::make('date')
                                                    ->label('Tanggal Stok')
                                                    ->columnSpan(6),

                                                TextInput::make('quantity')
                                                    ->label('Jumlah')
                                                    ->columnSpan(6)
                                                    ->numeric(),
                                            ]),
                                    ])->columnSpanFull(),



                                // Repeater::make('discounts')
                                //     ->hiddenLabel()
                                //     ->columns(12)
                                //     ->columnSpanFull()
                                //     ->addActionLabel('Tambah Diskon')
                                //     ->relationship('discounts')
                                //     ->addable(fn() => Auth::user()->hasRole('owner'))
                                //     ->deletable(fn() => Auth::user()->hasRole('owner'))
                                //     ->schema([
                                //         Select::make('store_id')
                                //             ->label('Toko')
                                //             ->columnSpanFull()
                                //             ->options(
                                //                 Store::query()
                                //                     ->when(!Auth::user()->hasRole('owner'), function ($query) {
                                //                         return $query->where('id', Auth::user()->store_id);
                                //                     })
                                //                     ->pluck('name', 'id')
                                //             )
                                //             ->searchable(),

                                //         Select::make('discount_type_id')
                                //             ->columnSpan(4)
                                //             ->label('Tipe Diskon')
                                //             ->options(DiscountType::pluck('name', 'id'))
                                //             ->searchable(),

                                //         Select::make('type')
                                //             ->columnSpan(4)
                                //             ->label('Tipe Diskon')
                                //             ->options([
                                //                 'percent' => 'Persen',
                                //                 'amount' => 'Nominal',
                                //             ])

                                //             ->searchable(),

                                //         TextInput::make('value')
                                //             ->columnSpan(4)
                                //             ->label('Nilai Diskon')
                                //             ->numeric()
                                //             ->required(),
                                //     ])
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
                                    ->icon(LucideIcon::Tag)
                                    ->description('Atur kategori, merk, dan satuan produk untuk memudahkan pengelompokan, filter, dan laporan stok.')
                                    ->inlineLabel()
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 4,
                                        'lg' => 4,
                                    ]) // <== lebar 8/12
                                    ->schema([
                                        Select::make('product_category_id')
                                            ->label('Kategori Produk')
                                            ->relationship('productCategory', 'name')
                                            ->options(ProductCategory::all()->pluck('name', 'id'))
                                            ->required()
                                            ->searchable(),
                                        Select::make('brand_id')
                                            ->label('Merk')
                                            ->relationship('brand', 'name')
                                            ->options(Brand::all()->pluck('name', 'id'))
                                            ->searchable(),
                                        Select::make('unit_id')
                                            // ->relationship('unit', 'name')
                                            ->label('Satuan')
                                            ->options(Unit::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->label('Nama Satuan')
                                                    ->required(),
                                                TextInput::make('symbol')
                                                    ->label('Simbol')
                                                    ->placeholder('Cont. pcs, box, kg, liter, dll')
                                                    ->required(),
                                            ]),
                                    ]),

                                Section::make('Foto Produk')
                                    ->icon(LucideIcon::Image)
                                    ->description('Unggah gambar produk berkualitas untuk memperjelas identitas barang pada katalog dan struk penjualan.')
                                    ->columnSpan([
                                        'xs' => 12,
                                        'sm' => 12,
                                        'md' => 4,
                                        'lg' => 4,
                                    ]) // <== lebar 8/12
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('images')
                                            ->hiddenLabel()
                                            ->collection('productImages')
                                            ->multiple()

                                    ]),

                            ]),

                    ]),
            ]);
    }
}
