<?php

namespace App\Filament\Clusters\Transactions\Resources\TransactionItems;

use App\Filament\Clusters\Transactions\Resources\TransactionItems\Pages\CreateTransactionItem;
use App\Filament\Clusters\Transactions\Resources\TransactionItems\Pages\EditTransactionItem;
use App\Filament\Clusters\Transactions\Resources\TransactionItems\Pages\ListTransactionItems;
use App\Filament\Clusters\Transactions\Resources\TransactionItems\Pages\ViewTransactionItem;
use App\Filament\Clusters\Transactions\Resources\TransactionItems\Schemas\TransactionItemForm;
use App\Filament\Clusters\Transactions\Resources\TransactionItems\Schemas\TransactionItemInfolist;
use App\Filament\Clusters\Transactions\Resources\TransactionItems\Tables\TransactionItemsTable;
use App\Filament\Clusters\Transactions\TransactionsCluster;
use App\Models\TransactionItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransactionItemResource extends Resource
{
    protected static ?string $model = TransactionItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Barang Keluar';
    protected static ?string $modelLabel = 'Barang Keluar';
    protected static ?string $pluralModelLabel = 'Barang Keluar';

    protected static ?string $cluster = TransactionsCluster::class;

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
