<?php

namespace App\Filament\Clusters\Reports;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class ReportsCluster extends Cluster
{
    protected static string | BackedEnum | null $navigationIcon = LucideIcon::PrinterCheck;
    protected static ?string $navigationLabel = 'Reports';
    protected static ?string $modelLabel = 'Reports';
    protected static ?string $pluralModelLabel = 'Reports';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
