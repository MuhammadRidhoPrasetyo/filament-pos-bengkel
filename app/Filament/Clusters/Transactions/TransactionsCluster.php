<?php

namespace App\Filament\Clusters\Transactions;

use UnitEnum;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class TransactionsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = LucideIcon::ReceiptText;
    protected static string | UnitEnum | null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $modelLabel = 'Penjualan';
    protected static ?string $pluralModelLabel = 'Penjualan';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
