<?php

namespace App\Filament\Clusters\Purchases;

use UnitEnum;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class PurchasesCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = LucideIcon::Package;

    protected static string | UnitEnum | null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pembelian';
    protected static ?string $modelLabel = 'Pembelian';
    protected static ?string $pluralModelLabel = 'Pembelian';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
