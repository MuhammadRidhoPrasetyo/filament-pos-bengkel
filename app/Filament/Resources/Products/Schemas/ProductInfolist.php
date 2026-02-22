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
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

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
                                Section::make('Informasi Produk')
                                    ->icon(LucideIcon::Package)
                                    ->description('Detail lengkap produk: nama, SKU, tipe, dan spesifikasi.')
                                    ->inlineLabel()
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('sku')
                                            ->label('SKU')
                                            ->badge()
                                            ->color('info')
                                            ->weight('semibold'),
                                        TextEntry::make('name')
                                            ->label('Nama Produk')
                                            ->weight('semibold'),
                                        TextEntry::make('type')
                                            ->label('Tipe Produk'),
                                        TextEntry::make('keyword')
                                            ->label('Kata Kunci')
                                            ->badge()
                                            ->color('success'),
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
                                Section::make('Klasifikasi Produk')
                                    ->icon(LucideIcon::Layers)
                                    ->description('Kategori, merk, dan satuan produk.')
                                    ->inlineLabel()
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('productCategory.name')
                                            ->label('Kategori Produk')
                                            ->badge()
                                            ->color('warning'),
                                        TextEntry::make('brand.name')
                                            ->label('Merk')
                                            ->badge()
                                            ->color('info'),
                                        TextEntry::make('unit.name')
                                            ->label('Satuan')
                                            ->badge()
                                            ->color('success'),
                                    ]),

                                Section::make('Label & Status')
                                    ->icon(LucideIcon::Tag)
                                    ->description('Label produk untuk identifikasi dan pengelolaannya.')
                                    ->inlineLabel()
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('label')
                                            ->label('Label Produk')
                                            ->columnSpanFull()
                                    ]),

                                Section::make('Galeri Produk')
                                    ->icon(LucideIcon::Image)
                                    ->description('Foto atau gambar produk untuk referensi visual.')
                                    ->columnSpanFull()
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
