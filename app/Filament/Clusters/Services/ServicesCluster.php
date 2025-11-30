<?php

namespace App\Filament\Clusters\Services;

use BackedEnum;
use UnitEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class ServicesCluster extends Cluster
{
    protected static string | BackedEnum | null $navigationIcon = LucideIcon::Wrench;
    protected static string | UnitEnum | null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Servis';
    protected static ?string $modelLabel = 'Servis';
    protected static ?string $pluralModelLabel = 'Servis';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
