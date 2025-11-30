<?php

namespace App\Filament\Clusters\Transactions\Resources\Transactions;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Clusters\Transactions\TransactionsCluster;
use App\Filament\Clusters\Transactions\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Clusters\Transactions\Resources\Transactions\Pages\ViewTransaction;
use App\Filament\Clusters\Transactions\Resources\Transactions\Pages\ListTransactions;
use App\Filament\Clusters\Transactions\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Clusters\Transactions\Resources\Transactions\Schemas\TransactionForm;
use App\Filament\Clusters\Transactions\Resources\Transactions\Tables\TransactionsTable;
use App\Filament\Clusters\Transactions\Resources\Transactions\Schemas\TransactionInfolist;


class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 1;
    protected static ?string $cluster = TransactionsCluster::class;
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $modelLabel = 'Penjualan';
    protected static ?string $pluralModelLabel = 'Penjualan';

    public static function form(Schema $schema): Schema
    {
        return TransactionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransactionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
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
            'index' => ListTransactions::route('/'),
            'create' => CreateTransaction::route('/create'),
            'view' => ViewTransaction::route('/{record}'),
            'edit' => EditTransaction::route('/{record}/edit'),
        ];
    }
}
