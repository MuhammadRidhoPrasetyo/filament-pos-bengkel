<?php

namespace App\Filament\Clusters\Reports\Resources\PurchaseItems\Widgets;


use App\Models\PurchaseItem;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Clusters\Reports\Resources\PurchaseItems\Pages\ListPurchaseItems;

class PurchaseItemsStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListPurchaseItems::class;
    }

    public function getColumns(): int | array
    {
        return [
            'md' => 12,
            'xl' => 12,
        ];
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        // Total purchase value for the current table filters
        try {
            $agg = clone $query;
            $agg->getQuery()->orders = null;
            $agg->getQuery()->limit = null;
            $agg->getQuery()->offset = null;

            $totalValue = (float) $agg->selectRaw('COALESCE(SUM(unit_purchase_price * quantity_ordered), 0) as total')->value('total');
        } catch (\Exception $e) {
            // fallback: compute in PHP
            $totalValue = (float) $query->get()->reduce(fn($carry, $item) => $carry + (($item->unit_purchase_price ?? 0) * ($item->quantity_ordered ?? 0)), 0);
        }

        // Total items purchased (quantity)
        try {
            $aggItems = clone $query;
            $aggItems->getQuery()->orders = null;
            $aggItems->getQuery()->limit = null;
            $aggItems->getQuery()->offset = null;

            $totalItems = (int) $aggItems->selectRaw('COALESCE(SUM(quantity_ordered), 0) as total_items')->value('total_items');
        } catch (\Exception $e) {
            $totalItems = (int) $query->sum('quantity_ordered');
        }

        // Today's purchase value
        $today = Carbon::now()->toDateString();
        $todayQuery = $this->getPageTableQuery();
        try {
            $tq = clone $todayQuery;
            $tq->getQuery()->orders = null;
            $tq->getQuery()->limit = null;
            $tq->getQuery()->offset = null;

            $todayValue = (float) $tq->whereRelation('purchase', 'purchase_date', $today)
                ->selectRaw("COALESCE(SUM(unit_purchase_price * quantity_ordered), 0) as total")
                ->value('total');
        } catch (\Exception $e) {
            $todayValue = (float) $todayQuery->whereRelation('purchase', 'purchase_date', $today)->get()->reduce(fn($carry, $item) => $carry + (($item->unit_purchase_price ?? 0) * ($item->quantity_ordered ?? 0)), 0);
        }

        $format = fn(float $amount): string => 'Rp ' . number_format($amount, 0, ',', '.');

        // Determine date range from table filters (if present)
        $filters = $this->tableFilters ?? [];

        $from = null;
        $to = null;

        if (isset($filters['from']) && is_array($filters['from'])) {
            $from = $filters['from']['from'] ?? null;
        }

        if (isset($filters['to']) && is_array($filters['to'])) {
            $to = $filters['to']['to'] ?? null;
        }

        // Fallback: try reading min/max purchase_date from query
        if (! $from || ! $to) {
            try {
                $minDate = $query->selectRaw('MIN(purchase.purchase_date) as min_date')->value('min_date');
                $maxDate = $query->selectRaw('MAX(purchase.purchase_date) as max_date')->value('max_date');

                if ($minDate && ! $from) {
                    $from = $minDate;
                }
                if ($maxDate && ! $to) {
                    $to = $maxDate;
                }
            } catch (\Exception $e) {
                // ignore
            }
        }

        $days = 1;
        try {
            if ($from && $to) {
                $start = Carbon::parse($from);
                $end = Carbon::parse($to);
                $days = max(1, $start->diffInDays($end) + 1);
            }
        } catch (\Exception $e) {
            $days = 1;
        }

        // Calculate previous period revenue (same-length period immediately before current)
        $prevRevenue = 0;
        try {
            if (isset($start) && isset($end)) {
                $prevEnd = $start->copy()->subDay();
                $prevStart = $prevEnd->copy()->subDays($days - 1);

                $prevQuery = PurchaseItem::query()
                    ->whereHas('purchase', function ($q) use ($prevStart, $prevEnd) {
                        $q->whereBetween('purchase_date', [$prevStart->toDateString(), $prevEnd->toDateString()]);
                    });

                // preserve store filter if present
                if (isset($filters['store_id'])) {
                    $storeId = is_array($filters['store_id']) ? ($filters['store_id']['store_id'] ?? ($filters['store_id'][0] ?? null)) : $filters['store_id'];
                    if ($storeId) {
                        $prevQuery->whereRelation('purchase', 'store_id', $storeId);
                    }
                }

                $prevRevenue = (float) $prevQuery->selectRaw('COALESCE(SUM(unit_purchase_price * quantity_ordered), 0) as total')->value('total');
            }
        } catch (\Exception $e) {
            $prevRevenue = 0;
        }

        // compute percent change for revenue
        if ($prevRevenue > 0) {
            $rPercent = (($totalValue - $prevRevenue) / abs($prevRevenue)) * 100;
            $rPercentLabel = number_format(abs($rPercent), 1) . '% ' . ($rPercent >= 0 ? 'Bertambah' : 'Berkurang');
            $rPercentIcon = $rPercent >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        } elseif ($totalValue > 0 && $prevRevenue == 0) {
            $rPercentLabel = 'New';
            $rPercentIcon = 'heroicon-m-arrow-trending-up';
        } else {
            $rPercentLabel = '0%';
            $rPercentIcon = 'heroicon-m-minus-small';
        }

        $rColor = $rPercentIcon === 'heroicon-m-arrow-trending-up' ? 'success' : ($rPercentIcon === 'heroicon-m-arrow-trending-down' ? 'danger' : null);

        return [
            Stat::make('Nilai Pembelian', $format($totalValue))
                ->description($rPercentLabel)
                ->descriptionIcon($rPercentIcon)
                ->color($rColor)
                ->columnSpanFull(),

            Stat::make('Jumlah Barang Dibeli', number_format($totalItems, 0, ',', '.'))
                ->description('Total qty dalam periode')
                ->columnSpan(4),

            Stat::make('Nilai Hari Ini', $format($todayValue))
                ->description('Hari ini')
                ->columnSpan(4),

            Stat::make("Rata-rata / Hari ({$days} hari)", $format($totalValue > 0 ? $totalValue / $days : 0))
                ->description('Rata-rata nilai pembelian per hari')
                ->columnSpan(4),
        ];
    }
}
