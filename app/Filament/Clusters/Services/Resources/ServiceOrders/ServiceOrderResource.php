<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders;


use BackedEnum;
use Filament\Tables\Table;
use App\Models\ServiceOrder;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Clusters\Services\ServicesCluster;
use App\Filament\Clusters\Services\Resources\ServiceOrders\Pages\EditServiceOrder;
use App\Filament\Clusters\Services\Resources\ServiceOrders\Pages\ViewServiceOrder;
use App\Filament\Clusters\Services\Resources\ServiceOrders\Pages\ListServiceOrders;
use App\Filament\Clusters\Services\Resources\ServiceOrders\Pages\CreateServiceOrder;
use App\Filament\Clusters\Services\Resources\ServiceOrders\Schemas\ServiceOrderForm;
use App\Filament\Clusters\Services\Resources\ServiceOrders\Tables\ServiceOrdersTable;
use App\Filament\Clusters\Services\Resources\ServiceOrders\Schemas\ServiceOrderInfolist;

class ServiceOrderResource extends Resource
{
    protected static ?string $model = ServiceOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 2;
    protected static ?string $cluster = ServicesCluster::class;
    protected static ?string $navigationLabel = 'Daftar Servis';
    protected static ?string $modelLabel = 'Daftar Servis';
    protected static ?string $pluralModelLabel = 'Daftar Servis';

    public static function form(Schema $schema): Schema
    {
        return ServiceOrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceOrdersTable::configure($table);
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
            'index' => ListServiceOrders::route('/'),
            'create' => CreateServiceOrder::route('/create'),
            'view' => ViewServiceOrder::route('/{record}'),
            'edit' => EditServiceOrder::route('/{record}/edit'),
        ];
    }

}
