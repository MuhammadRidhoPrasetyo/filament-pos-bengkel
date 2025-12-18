<?php

namespace App\Filament\Clusters\Reports\Resources\ServicesPerMechanics;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\TransactionItem;
use Filament\Resources\Resource;
use App\Models\ServicesPerMechanics;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Pages\EditServicesPerMechanics;
use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Pages\ListServicesPerMechanics;
use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Pages\ViewServicesPerMechanics;
use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Pages\CreateServicesPerMechanics;
use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Schemas\ServicesPerMechanicsForm;
use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Tables\ServicesPerMechanicsTable;
use App\Filament\Clusters\Reports\Resources\ServicesPerMechanics\Schemas\ServicesPerMechanicsInfolist;

class ServicesPerMechanicsResource extends Resource
{
    protected static ?string $model = TransactionItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Jasa Per Mekanik';
    protected static ?string $modelLabel = 'Jasa Per Mekanik';
    protected static ?string $pluralModelLabel = 'Jasa Per Mekanik';

    protected static ?string $cluster = ReportsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return ServicesPerMechanicsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServicesPerMechanicsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesPerMechanicsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereRelation('product.productCategory', 'item_type', 'labor');
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
            'index' => ListServicesPerMechanics::route('/'),
            'create' => CreateServicesPerMechanics::route('/create'),
            'view' => ViewServicesPerMechanics::route('/{record}'),
            'edit' => EditServicesPerMechanics::route('/{record}/edit'),
        ];
    }
}
