<?php

namespace App\Filament\Widgets;

use App\Models\ProductStock;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockInformationsStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $base = ProductStock::query()
        ->whereRelation('product.productCategory', 'item_type', 'part')
            ->where('store_id', auth()->user()->store_id);

        $nearPct = 0.25;

        // totals
        $totalWithMin = (int) (clone $base)
            ->whereNotNull('minimum_stock')
            ->where('minimum_stock', '>', 0)
            ->count();

        $nearCount = (int) (clone $base)
            ->whereNotNull('minimum_stock')
            ->where('minimum_stock', '>', 0)
            ->whereColumn('quantity', '>', 'minimum_stock')
            ->whereRaw('quantity <= minimum_stock + (minimum_stock * ?)', [$nearPct])
            ->count();

        $belowCount = (int) (clone $base)
            ->whereNotNull('minimum_stock')
            ->where('minimum_stock', '>', 0)
            ->whereColumn('quantity', '<=', 'minimum_stock')
            ->count();

        $outCount = (int) (clone $base)
            ->where('quantity', 0)
            ->count();

        $totalSkus = (int) (clone $base)->count();

        $nearPctDisplay = $totalWithMin > 0 ? round($nearCount / $totalWithMin * 100, 1) : 0;
        $belowPctDisplay = $totalWithMin > 0 ? round($belowCount / $totalWithMin * 100, 1) : 0;
        $outPctDisplay = $totalSkus > 0 ? round($outCount / $totalSkus * 100, 1) : 0;

        // average stock (for display)
        $avgStock = (float) (clone $base)->avg('quantity') ?: 0;

        return [
            // Stat::make('Stok Rata-rata', number_format($avgStock, 0, ',', '.'))
            //     ->description('Rata-rata stok per produk')
            //     ->icon('heroicon-o-scale')
            //     ->color('primary')
            //     ->columnSpanFull(),

            Stat::make('Mendekati Minimal', $nearCount)
                ->description($nearPctDisplay . '% dari produk berdasar min')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->icon('heroicon-o-clock')
                ->color('warning')
                ,

            Stat::make('Di Bawah Minimal', $belowCount)
                ->description($belowPctDisplay . '% dari produk berdasar min')
                ->descriptionIcon('heroicon-m-fire')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('danger')
                ,

            Stat::make('Habis (0)', $outCount)
                ->description($outPctDisplay . '% dari semua SKU')
                ->descriptionIcon('heroicon-m-x-circle')
                ->icon('heroicon-o-archive-box-x-mark')
                ->color('danger')
                ,
        ];
    }
}
