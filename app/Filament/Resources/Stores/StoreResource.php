<?php

namespace App\Filament\Resources\Stores;

use BackedEnum;
use App\Models\Store;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Stores\Pages\EditStore;
use App\Filament\Resources\Stores\Pages\ViewStore;
use App\Filament\Resources\Stores\Pages\ListStores;
use App\Filament\Resources\Stores\Pages\CreateStore;
use App\Filament\Resources\Stores\Schemas\StoreForm;
use App\Filament\Resources\Stores\Tables\StoresTable;
use App\Filament\Resources\Stores\Schemas\StoreInfolist;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingStorefront;
    protected static ?string $navigationLabel = 'Bengkel';
    protected static ?string $modelLabel = 'Bengkel';
    protected static ?string $pluralModelLabel = 'Bengkel';

    public static function form(Schema $schema): Schema
    {
        return StoreForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStores::route('/'),
            'create' => CreateStore::route('/create'),
            'view' => ViewStore::route('/{record}'),
            'edit' => EditStore::route('/{record}/edit'),
        ];
    }
}
