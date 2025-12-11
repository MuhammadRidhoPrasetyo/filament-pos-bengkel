<?php

namespace App\Filament\Clusters\Reports\Resources\TransactionItems;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\TransactionItem;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Pages\EditTransactionItem;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Pages\ViewTransactionItem;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Pages\ListTransactionItems;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Pages\CreateTransactionItem;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Schemas\TransactionItemForm;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Tables\TransactionItemsTable;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Schemas\TransactionItemInfolist;

class TransactionItemResource extends Resource
{
    protected static ?string $model = TransactionItem::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Barang Keluar';
    protected static ?string $modelLabel = 'Barang Keluar';
    protected static ?string $pluralModelLabel = 'Barang Keluar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = ReportsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return TransactionItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransactionItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionItemsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereRelation('product.productCategory', 'item_type', 'part')
        ;
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
            'index' => ListTransactionItems::route('/'),
            'create' => CreateTransactionItem::route('/create'),
            'view' => ViewTransactionItem::route('/{record}'),
            'edit' => EditTransactionItem::route('/{record}/edit'),
        ];
    }
}
