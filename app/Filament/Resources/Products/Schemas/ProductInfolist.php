<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Support\Str;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class ProductInfolist
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
                            ->columns(12)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                                'xl' => 8,
                            ])->schema([
                                Section::make('Details')
                                    ->inlineLabel()
                                    ->columnSpanFull() // <== lebar 8/12
                                    ->schema([
                                        TextEntry::make('sku')
                                            ->label('SKU'),
                                        TextEntry::make('name')
                                            ->label('Nama Produk'),
                                        TextEntry::make('type')
                                            ->label('Tipe Produk'),
                                        TextEntry::make('keyword')
                                            ->label('Kata Kunci'),
                                        TextEntry::make('compatibility')
                                            ->label('Kompatibilitas'),
                                        TextEntry::make('size')
                                            ->label('Ukuran'),
                                        TextEntry::make('created_at')
                                            ->dateTime()
                                            ->hidden(),
                                        TextEntry::make('updated_at')
                                            ->dateTime()
                                            ->hidden(),
                                    ]),
                            ])
                            ->columnSpanFull(),

                        Grid::make()
                            ->columns(12)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 4,
                                'lg' => 4,
                                'xl' => 4,
                            ])->schema([
                                Section::make('Detail')
                                    ->icon('heroicon-o-information-circle')
                                    ->inlineLabel()
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('productCategory.name')
                                            ->label('Kategori Produk'),
                                        TextEntry::make('brand.name')
                                            ->label('Merk'),
                                        TextEntry::make('unit.name')
                                            ->label('Satuan'),
                                    ]),

                                Section::make('Label')
                                    ->icon('heroicon-o-tag')
                                    ->inlineLabel()
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('productLabel.display_name')
                                            ->label('Label Produk')
                                            ,
                                    ]),

                                Section::make('Foto Produk')
                                    ->hiddenLabel()
                                    ->columnSpanFull() // <== lebar 8/12
                                    ->schema([
                                        SpatieMediaLibraryImageEntry::make('avatar')
                                            ->hiddenLabel()
                                            ->collection('productImages')
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
