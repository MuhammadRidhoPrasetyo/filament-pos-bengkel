<?php

namespace App\Filament\Resources\ProductCategories;

use App\Filament\Resources\ProductCategories\Pages\ManageProductCategories;
use App\Models\ProductCategory;
use BackedEnum;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static string|BackedEnum|null $navigationIcon = LucideIcon::Tag;
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';
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
                Select::make('parent_id')
                    ->label('Parent Kategori')
                    ->options(
                        ProductCategory::query()->pluck('name', 'id')
                    )
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->columnSpanFull(),
                Select::make('pricing_mode')
                    ->label('Tipe Harga')
                    ->options([
                        'fixed' => 'Harga Tetap',
                        'editable' => 'Harga Bisa Diubah',
                    ])
                    ->required()
                    ->columnSpanFull(),
                Select::make('item_type')
                    ->label('Tipe Produk')
                    ->options([
                        'part' => 'Produk',
                        'labor' => 'Jasa',
                    ])
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(12)
                            ->columnSpanFull()
                            ->schema([
                                Section::make('Detail Kategori')
                                    ->icon(LucideIcon::Tag)
                                    ->description('Informasi dasar kategori dan hirarki (parent / subkategori).')
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Kategori Produk')
                                            ->weight('semibold'),

                                        TextEntry::make('parent.name')
                                            ->label('Parent Kategori')
                                            ->helperText('Jika kosong berarti kategori root'),

                                        TextEntry::make('children_names')
                                            ->label('Sub Kategori')
                                            ->helperText(fn($record) => $record->children->isNotEmpty() ? $record->children->count().' subkategori: ' . $record->children_names : 'Tidak ada subkategori')
                                            ->wrap(),
                                    ]),
                            ]),

                        Grid::make()
                            ->columns(12)
                            ->columnSpanFull()
                            ->schema([
                                Section::make('Pengaturan & Metadata')
                                    ->icon(LucideIcon::Settings)
                                    ->description('Tipe produk dan mode harga untuk kategori ini.')
                                    ->inlineLabel()
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('item_type')
                                            ->label('Tipe Produk')
                                            ->formatStateUsing(fn($state) => $state == 'part' ? 'Produk' : 'Jasa')
                                            ->badge()
                                            ->color(fn($state) => $state == 'part' ? 'primary' : 'warning')
                                            ->columnSpan(6),

                                        TextEntry::make('pricing_mode')
                                            ->label('Tipe Harga')
                                            ->formatStateUsing(fn($state) => $state == 'fixed' ? 'Harga Tetap' : 'Harga Bisa Diubah')
                                            ->badge()
                                            ->color(fn($state) => $state == 'fixed' ? 'success' : 'secondary')
                                            ->columnSpan(6),

                                        TextEntry::make('children_count')
                                            ->label('Jumlah Sub')
                                            ->formatStateUsing(fn($state) => $state)
                                            ->columnSpan(6),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kategori Produk')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->parent?->name ? sprintf('Parent: %s', $record->parent->name) : null),

                TextColumn::make('parent.name')
                    ->label('Parent Kategori')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('children_count')
                    ->label('Sub')
                    ->counts('children')
                    ->sortable(),

                IconColumn::make('item_type')
                    ->label('Tipe')
                    ->icon(fn(string $state) => match ($state) {
                        'part' => LucideIcon::Package,
                        'labor' => LucideIcon::Wrench,
                        default => LucideIcon::CircleQuestionMark,
                    })
                    ->colors([
                        'primary' => fn($state) => $state === 'part',
                        'warning' => fn($state) => $state === 'labor',
                    ])
                    ->sortable(),

                IconColumn::make('pricing_mode')
                    ->label('Tipe Harga')
                    ->icon(fn(string $state) => match ($state) {
                        'fixed' => LucideIcon::CheckCircle,
                        'editable' => LucideIcon::Edit2,
                        default => LucideIcon::CircleQuestionMark,
                    })
                    ->colors([
                        'success' => fn($state) => $state === 'fixed',
                        'secondary' => fn($state) => $state === 'editable',
                    ])
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('item_type')
                    ->options([
                        'part' => 'Produk',
                        'labor' => 'Jasa',
                    ]),

                SelectFilter::make('pricing_mode')
                    ->options([
                        'fixed' => 'Harga Tetap',
                        'editable' => 'Harga Bisa Diubah',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
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
