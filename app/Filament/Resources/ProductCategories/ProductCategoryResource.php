<?php

namespace App\Filament\Resources\ProductCategories;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\ProductCategory;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ProductCategories\Pages\ManageProductCategories;
use Filament\Forms\Components\Select;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::EllipsisHorizontalCircle;
    protected static ?string $navigationLabel = 'Kategori Produk';
    protected static ?string $modelLabel = 'Kategori Produk';
    protected static ?string $pluralModelLabel = 'Kategori Produk';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Kategori Produk')
                    ->placeholder('Spare Part, Makanan/Minuman, Servis, dll')
                    ->required()
                    ->columnSpanFull(),
                Select::make('pricing_mode')
                    ->label('Tipe Harga')
                    ->options([
                        'fixed' => 'Harga Tetap',
                        'editable' => 'Harga Bisa Diubah',
                    ])
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Kategori Produk')
                    ->columnSpanFull(),

                TextEntry::make('pricing_mode')
                    ->label('Tipe Harga')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kategori Produk')
                    ->searchable(),

                TextColumn::make('pricing_mode')
                    ->label('Tipe Harga')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProductCategories::route('/'),
        ];
    }
}
