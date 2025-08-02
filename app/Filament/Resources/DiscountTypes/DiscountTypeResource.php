<?php

namespace App\Filament\Resources\DiscountTypes;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\DiscountType;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\DiscountTypes\Pages\ManageDiscountTypes;
use Filament\Infolists\Components\TextEntry;

class DiscountTypeResource extends Resource
{
    protected static ?string $model = DiscountType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PercentBadge;
    protected static ?string $navigationLabel = 'Jenis Diskon';
    protected static ?string $modelLabel = 'Jenis Diskon';
    protected static ?string $pluralModelLabel = 'Jenis Diskon';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->placeholder('cont. diskon untuk pelanggan baru')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Tipe Diskon')
                    ->columnSpanFull(),

                TextEntry::make('description')
                    ->label('Keterangan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tipe Diskon')
                    ->searchable()
                    ->description(fn(DiscountType $record): string => $record->description),
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
            'index' => ManageDiscountTypes::route('/'),
        ];
    }
}
