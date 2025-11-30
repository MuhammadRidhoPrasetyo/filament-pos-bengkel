<?php

namespace App\Filament\Resources\ProductStocks;

use BackedEnum;
use UnitEnum;
use Filament\Tables\Table;
use App\Models\ProductStock;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use App\Filament\Resources\ProductStocks\Pages\EditProductStock;
use App\Filament\Resources\ProductStocks\Pages\ViewProductStock;
use App\Filament\Resources\ProductStocks\Pages\ListProductStocks;
use App\Filament\Resources\ProductStocks\Pages\CreateProductStock;
use App\Filament\Resources\ProductStocks\Schemas\ProductStockForm;
use App\Filament\Resources\ProductStocks\Tables\ProductStocksTable;
use App\Filament\Resources\ProductStocks\Schemas\ProductStockInfolist;
use App\Filament\Resources\ProductStocks\RelationManagers\ProductRelationManager;
use App\Filament\Resources\ProductStocks\RelationManagers\DiscountRelationManager;
use App\Filament\Resources\ProductStocks\RelationManagers\ProductPriceRelationManager;
use App\Filament\Resources\ProductStocks\RelationManagers\PurchaseItemsRelationManager;
use App\Filament\Resources\ProductStocks\RelationManagers\StockAdjustmentItemsRelationManager;
use App\Filament\Resources\ProductStocks\RelationManagers\ProductPriceHistoriesRelationManager;

class ProductStockResource extends Resource
{
    protected static ?string $model = ProductStock::class;

    protected static string|BackedEnum|null $navigationIcon = LucideIcon::Package2;
    protected static string | UnitEnum | null $navigationGroup = 'Stok';
    protected static ?string $navigationLabel = 'Stok Barang';
    protected static ?string $modelLabel = 'Stok Barang';
    protected static ?string $pluralModelLabel = 'Stok Barang';

    public static function form(Schema $schema): Schema
    {
        return ProductStockForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductStockInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductStocksTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(!Auth::user()->hasRole('owner'), function ($query) {
                return $query->where('store_id', Auth::user()->store_id);
            });
    }

    public static function getRelations(): array
    {
        return [
            'diskon' => DiscountRelationManager::class,
            'harga' => ProductPriceRelationManager::class,
            'riwayat-harga' => ProductPriceHistoriesRelationManager::class,
            'riwayat-barang-masuk' => PurchaseItemsRelationManager::class,
            'penysuaian-stok' => StockAdjustmentItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductStocks::route('/'),
            // 'create' => CreateProductStock::route('/create'),
            'view' => ViewProductStock::route('/{record}'),
            'edit' => EditProductStock::route('/{record}/edit'),
        ];
    }
}
