<?php

namespace App\Filament\Clusters\Reports\Resources\TransactionItems\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Carbon;
use App\Filament\Clusters\Reports\Resources\TransactionItems\Pages\ListTransactionItems;

class TransactionItemsStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListTransactionItems::class;
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

        $totalCount = $query->count();
        $totalRevenue = (float) $query->sum('line_total');
        $totalProfit = (float) $query->sum('line_profit');

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

        // Fallback: try to get min/max transaction date from the query itself
        if (! $from || ! $to) {
            try {
                $minDate = $query->selectRaw('MIN(transaction.transaction_date) as min_date')->value('min_date');
                $maxDate = $query->selectRaw('MAX(transaction.transaction_date) as max_date')->value('max_date');

                if ($minDate && ! $from) {
                    $from = $minDate;
                }
                if ($maxDate && ! $to) {
                    $to = $maxDate;
                }
            } catch (\Exception $e) {
                // ignore and fallback to single-day calculation
            }
        }

        $days = 1;

        try {
            if ($from && $to) {
                $start = Carbon::parse($from);
                $end = Carbon::parse($to);
                $days = max(1, $start->diffInDays($end) + 1); // inclusive
            }
        } catch (\Exception $e) {
            $days = 1;
        }

        $perDayRevenue = $days > 0 ? $totalRevenue / $days : 0;
        $perDayProfit = $days > 0 ? $totalProfit / $days : 0;

        // Calculate previous period profit and revenue (same-length period immediately before current)
        $prevProfit = 0;
        $prevRevenue = 0;
        try {
            if (isset($start) && isset($end)) {
                $prevEnd = $start->copy()->subDay();
                $prevStart = $prevEnd->copy()->subDays($days - 1);

                $storeId = null;
                if (isset($filters['store_id'])) {
                    if (is_array($filters['store_id'])) {
                        $storeId = $filters['store_id']['store_id'] ?? ($filters['store_id'][0] ?? null);
                    } else {
                        $storeId = $filters['store_id'];
                    }
                }

                $prevQuery = \App\Models\TransactionItem::query()
                    ->whereHas('transaction', function ($q) use ($prevStart, $prevEnd) {
                        $q->whereBetween('transaction_date', [$prevStart->toDateString(), $prevEnd->toDateString()]);
                    });

                if ($storeId) {
                    $prevQuery->whereRelation('transaction', 'store_id', $storeId);
                }

                $prevProfit = (float) $prevQuery->sum('line_profit');
                $prevRevenue = (float) $prevQuery->sum('line_total');
            }
        } catch (\Exception $e) {
            $prevProfit = 0;
        }

        // compute percent change for profit
        if ($prevProfit > 0) {
            $percent = (($totalProfit - $prevProfit) / abs($prevProfit)) * 100;
            $percentLabel = number_format(abs($percent), 1) . '% ' . ($percent >= 0 ? 'Bertambah' : 'Berkurang');
            $percentIcon = $percent >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        } elseif ($totalProfit > 0 && $prevProfit == 0) {
            $percentLabel = 'New';
            $percentIcon = 'heroicon-m-arrow-trending-up';
        } else {
            $percentLabel = '0%';
            $percentIcon = 'heroicon-m-minus-small';
        }

        // compute percent change for revenue
        if ($prevRevenue > 0) {
            $rPercent = (($totalRevenue - $prevRevenue) / abs($prevRevenue)) * 100;
            $rPercentLabel = number_format(abs($rPercent), 1) . '% ' . ($rPercent >= 0 ? 'Bertambah' : 'Berkurang');
            $rPercentIcon = $rPercent >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        } elseif ($totalRevenue > 0 && $prevRevenue == 0) {
            $rPercentLabel = 'New';
            $rPercentIcon = 'heroicon-m-arrow-trending-up';
        } else {
            $rPercentLabel = '0%';
            $rPercentIcon = 'heroicon-m-minus-small';
        }

        return [
            Stat::make('Total Penjualan', $totalCount)
                ->columnSpanFull(),
            Stat::make('Total Pendapatan', $format($totalRevenue))
                ->description($rPercentLabel)
                ->descriptionIcon($rPercentIcon)
                ->color($rPercentIcon === 'heroicon-m-arrow-trending-up' ? 'success' : ($rPercentIcon === 'heroicon-m-arrow-trending-down' ? 'danger' : null))
                ->columnSpan(3),
            Stat::make('Total Keuntungan', $format($totalProfit))
                ->description($percentLabel)
                ->descriptionIcon($percentIcon)
                ->color($rPercentIcon === 'heroicon-m-arrow-trending-up' ? 'success' : ($rPercentIcon === 'heroicon-m-arrow-trending-down' ? 'danger' : null))
                ->columnSpan(3),
            Stat::make("Rata-rata Pendapatan / Hari ({$days} hari)", $format($perDayRevenue))
                ->columnSpan(3),
            Stat::make("Rata-rata Keuntungan / Hari ({$days} hari)", $format($perDayProfit))
                ->columnSpan(3),
        ];
    }
}
