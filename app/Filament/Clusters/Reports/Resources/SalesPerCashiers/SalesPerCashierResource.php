<?php

namespace App\Filament\Clusters\Reports\Resources\SalesPerCashiers;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\SalesPerCashier;
use App\Models\TransactionItem;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Pages\EditSalesPerCashier;
use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Pages\ViewSalesPerCashier;
use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Pages\ListSalesPerCashiers;
use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Pages\CreateSalesPerCashier;
use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Schemas\SalesPerCashierForm;
use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Tables\SalesPerCashiersTable;
use App\Filament\Clusters\Reports\Resources\SalesPerCashiers\Schemas\SalesPerCashierInfolist;

class SalesPerCashierResource extends Resource
{
    protected static ?string $model = TransactionItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Penjualan Per Kasir';
    protected static ?string $modelLabel = 'Penjualan Per Kasir';
    protected static ?string $pluralModelLabel = 'Penjualan Per Kasir';

    protected static ?string $cluster = ReportsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return SalesPerCashierForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SalesPerCashierInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesPerCashiersTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereRelation('product.productCategory', 'item_type', 'part');
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
            'index' => ListSalesPerCashiers::route('/'),
            'create' => CreateSalesPerCashier::route('/create'),
            'view' => ViewSalesPerCashier::route('/{record}'),
            'edit' => EditSalesPerCashier::route('/{record}/edit'),
        ];
    }
}
