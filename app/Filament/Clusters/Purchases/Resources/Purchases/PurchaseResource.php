<?php

namespace App\Filament\Clusters\Purchases\Resources\Purchases;

use BackedEnum;

use App\Models\Purchase;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Clusters\Purchases\PurchasesCluster;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use App\Filament\Clusters\Purchases\Resources\Purchases\Pages\EditPurchase;
use App\Filament\Clusters\Purchases\Resources\Purchases\Pages\ViewPurchase;
use App\Filament\Clusters\Purchases\Resources\Purchases\Pages\ListPurchases;
use App\Filament\Clusters\Purchases\Resources\Purchases\Pages\CreatePurchase;
use App\Filament\Clusters\Purchases\Resources\Purchases\Schemas\PurchaseForm;
use App\Filament\Clusters\Purchases\Resources\Purchases\Tables\PurchasesTable;
use App\Filament\Clusters\Purchases\Resources\Purchases\Schemas\PurchaseInfolist;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = LucideIcon::Wallet;
    protected static ?string $cluster = PurchasesCluster::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Pembelian';
    protected static ?string $modelLabel = 'Pembelian';
    protected static ?string $pluralModelLabel = 'Pembelian';

    public static function form(Schema $schema): Schema
    {
        return PurchaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PurchaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchasesTable::configure($table);
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
            'index' => ListPurchases::route('/'),
            'create' => CreatePurchase::route('/create'),
            'view' => ViewPurchase::route('/{record}'),
            'edit' => EditPurchase::route('/{record}/edit'),
        ];
    }
}
