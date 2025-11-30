<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrderUnits;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\ServiceOrderUnit;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Clusters\Services\ServicesCluster;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Pages\EditServiceOrderUnit;
use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Pages\ViewServiceOrderUnit;
use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Pages\ListServiceOrderUnits;
use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Pages\CreateServiceOrderUnit;
use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Schemas\ServiceOrderUnitForm;
use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Tables\ServiceOrderUnitsTable;
use App\Filament\Clusters\Services\Resources\ServiceOrderUnits\Schemas\ServiceOrderUnitInfolist;

class ServiceOrderUnitResource extends Resource
{
    protected static ?string $model = ServiceOrderUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = ServicesCluster::class;

    public static function form(Schema $schema): Schema
    {
        return ServiceOrderUnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceOrderUnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceOrderUnitsTable::configure($table);
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
            'index' => ListServiceOrderUnits::route('/'),
            'create' => CreateServiceOrderUnit::route('/create'),
            'view' => ViewServiceOrderUnit::route('/{record}'),
            'edit' => EditServiceOrderUnit::route('/{record}/edit'),
        ];
    }
}
