<?php

namespace App\Filament\Resources\CashFlowCategories;

use App\Filament\Resources\CashFlowCategories\Pages\ManageCashFlowCategories;
use App\Models\CashFlowCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\Grid as InfolistGrid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class CashFlowCategoryResource extends Resource
{
    protected static ?string $model = CashFlowCategory::class;

    protected static string|BackedEnum|null $navigationIcon = LucideIcon::Coins;
    protected static string | UnitEnum | null $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Kategori Alur Kas';
    protected static ?string $modelLabel = 'Kategori Alur Kas';
    protected static ?string $pluralModelLabel = 'Kategori Alur Kas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->icon(LucideIcon::Coins)
                    ->description('Kelola detail kategori alur kas (pemasukan / pengeluaran)')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->placeholder('Misal: Gaji Karyawan, Listrik, Biaya Admin')
                                    ->required()
                                    ->maxLength(255),
                                
                                ToggleButtons::make('type')
                                    ->label('Jenis Alur Kas')
                                    ->options([
                                        'income' => 'Pemasukan',
                                        'expense' => 'Pengeluaran',
                                    ])
                                    ->colors([
                                        'income' => 'success',
                                        'expense' => 'danger',
                                    ])
                                    ->icons([
                                        'income' => 'heroicon-m-arrow-trending-up',
                                        'expense' => 'heroicon-m-arrow-trending-down',
                                    ])
                                    ->inline()
                                    ->required(),
                            ]),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tambahkan keterangan tambahan jika ada...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->default(null),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Apakah kategori ini masih bisa digunakan untuk input transaksi baru?')
                            ->default(true)
                            ->required(),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Kategori')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Kategori')
                                    ->icon('heroicon-m-tag')
                                    ->weight('bold')
                                    ->size('lg'),
                                
                                TextEntry::make('type')
                                    ->label('Jenis Alur Kas')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'income' => 'Pemasukan (Income)',
                                        'expense' => 'Pengeluaran (Expense)',
                                        default => $state,
                                    })
                                    ->color(fn (string $state): string => match ($state) {
                                        'income' => 'success',
                                        'expense' => 'danger',
                                        default => 'gray',
                                    })
                                    ->icon(fn (string $state): string => match ($state) {
                                        'income' => 'heroicon-m-arrow-trending-up',
                                        'expense' => 'heroicon-m-arrow-trending-down',
                                        default => 'heroicon-m-question-mark-circle',
                                    }),

                                IconEntry::make('is_active')
                                    ->label('Status Aktif')
                                    ->boolean(),

                                TextEntry::make('description')
                                    ->label('Deskripsi')
                                    ->columnSpanFull()
                                    ->placeholder('Tidak ada keterangan tambahan'),
                            ]),
                    ])
                    ->columnSpanFull(),
                
                Section::make('Informasi Tambahan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d M Y, H:i')
                                    ->placeholder('-'),
                                
                                TextEntry::make('updated_at')
                                    ->label('Terakhir Diperbarui')
                                    ->dateTime('d M Y, H:i')
                                    ->placeholder('-'),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('type')
                    ->label('Jenis Kas')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'income' => 'heroicon-m-arrow-trending-up',
                        'expense' => 'heroicon-m-arrow-trending-down',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(50),
                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->hidden(fn ($record) => $record->is_system),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record->is_system),
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
            'index' => ManageCashFlowCategories::route('/'),
        ];
    }
}
