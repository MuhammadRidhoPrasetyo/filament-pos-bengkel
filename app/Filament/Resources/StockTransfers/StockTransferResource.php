<?php

namespace App\Filament\Resources\StockTransfers;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\StockTransfer;
use Filament\Resources\Resource;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use App\Filament\Resources\StockTransfers\Pages\ViewStockTransfer;
use App\Filament\Resources\StockTransfers\Pages\ListStockTransfers;
use App\Filament\Resources\StockTransfers\Pages\CreateStockTransfer;
use App\Filament\Resources\StockTransfers\Schemas\StockTransferForm;
use App\Filament\Resources\StockTransfers\Tables\StockTransfersTable;
use App\Filament\Resources\StockTransfers\Schemas\StockTransferInfolist;

class StockTransferResource extends Resource
{
    protected static ?string $model = StockTransfer::class;

    protected static string|BackedEnum|null $navigationIcon = LucideIcon::RefreshCcw;
    protected static string | UnitEnum | null $navigationGroup = 'Stok';
    protected static ?string $navigationLabel = 'Transfer Stok';
    protected static ?string $modelLabel = 'Transfer Stok';
    protected static ?string $pluralModelLabel = 'Transfer Stok';

    public static function form(Schema $schema): Schema
    {
        return StockTransferForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockTransferInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTransfersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockTransfers::route('/'),
            'create' => CreateStockTransfer::route('/create'),
            'view' => ViewStockTransfer::route('/{record}'),
        ];
    }
}
