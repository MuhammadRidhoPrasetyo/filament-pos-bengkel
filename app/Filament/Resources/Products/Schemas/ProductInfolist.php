<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

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
                        Section::make('Details')
                            ->inlineLabel()
                            ->columnSpan([
                                'xs' => 12,
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ]) // <== lebar 8/12
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
                        Section::make('Details')
                            ->inlineLabel()
                            ->columnSpan([
                                'xs' => 12,
                                'sm' => 12,
                                'md' => 4,
                                'lg' => 4,
                            ]) // <== lebar 8/12
                            ->schema([
                                TextEntry::make('productCategory.name')
                                    ->label('Kategori Produk'),
                                TextEntry::make('brand.name')
                                    ->label('Merk'),
                                TextEntry::make('unit.symbol')
                                    ->label('Satuan'),
                            ]),
                    ]),



            ]);
    }
}
